<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KamusNormalisasi;
use Maatwebsite\Excel\Facades\Excel;

class KamusNormalisasiController extends Controller
{
  public function index(Request $request)
  {
    $query = KamusNormalisasi::query();

    // Fitur pencarian
    if ($request->has('search') && $request->search != '') {
      $query->where('kata_tidak_baku', 'like', '%' . $request->search . '%')
        ->orWhere('kata_baku', 'like', '%' . $request->search . '%');
    }

    $kamus = $query->latest()->paginate(10);
    return view('admin.kamus.index', compact('kamus'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'kata_tidak_baku' => 'required|unique:kamus_normalisasis,kata_tidak_baku|string|max:255',
      'kata_baku' => 'required|string|max:255',
    ], [
      'kata_tidak_baku.unique' => 'Kata tidak baku ini sudah ada di dalam kamus!'
    ]);

    KamusNormalisasi::create([
      'kata_tidak_baku' => strtolower(trim($request->kata_tidak_baku)),
      'kata_baku' => strtolower(trim($request->kata_baku)),
    ]);

    return back()->with('success', 'Kata berhasil ditambahkan ke kamus!');
  }

  public function update(Request $request, $id)
  {
    $kamus = KamusNormalisasi::findOrFail($id);

    $request->validate([
      'kata_tidak_baku' => 'required|string|max:255|unique:kamus_normalisasis,kata_tidak_baku,' . $id,
      'kata_baku' => 'required|string|max:255',
    ]);

    $kamus->update([
      'kata_tidak_baku' => strtolower(trim($request->kata_tidak_baku)),
      'kata_baku' => strtolower(trim($request->kata_baku)),
    ]);

    return back()->with('success', 'Kamus berhasil diperbarui!');
  }

  public function destroy($id)
  {
    $kamus = KamusNormalisasi::findOrFail($id);
    $kamus->delete();

    return response()->json([
      'success' => true,
      'message' => 'Kata berhasil dihapus dari kamus!'
    ]);
  }

  public function import(Request $request)
  {
    $request->validate([
      'file_import' => 'required|mimes:csv,xlsx,xls|max:5120',
    ], [
      'file_import.required' => 'File wajib dipilih!',
      'file_import.mimes' => 'Format file harus CSV, XLSX, atau XLS!',
      'file_import.max' => 'Ukuran file maksimal 5 MB!',
    ]);

    try {
      $file = $request->file('file_import');
      $dataArray = Excel::toArray([], $file);
      $rows = $dataArray[0];
      array_shift($rows); 

      $berhasil = 0;
      $duplikat = 0;
      $kosong = 0;

      foreach ($rows as $row) {
        $kataTidakBaku = isset($row[0]) ? strtolower(trim($row[0])) : null;
        $kataBaku = isset($row[1]) ? strtolower(trim($row[1])) : null;

        if (!$kataTidakBaku || !$kataBaku) {
          $kosong++;
          continue;
        }

        // Skip jika sudah ada
        $sudahAda = KamusNormalisasi::where('kata_tidak_baku', $kataTidakBaku)->exists();
        if ($sudahAda) {
          $duplikat++;
          continue;
        }

        KamusNormalisasi::create([
          'kata_tidak_baku' => $kataTidakBaku,
          'kata_baku' => $kataBaku,
        ]);
        $berhasil++;
      }

      $pesan = "Import selesai: {$berhasil} kata berhasil ditambahkan";
      if ($duplikat > 0)
        $pesan .= ", {$duplikat} duplikat dilewati";
      if ($kosong > 0)
        $pesan .= ", {$kosong} baris kosong dilewati";

      return response()->json([
        'success' => true,
        'message' => $pesan,
        'stats' => compact('berhasil', 'duplikat', 'kosong')
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Gagal import: ' . $e->getMessage()
      ], 500);
    }
  }

  public function downloadTemplateKamus()
  {
    $headers = ['Content-Type' => 'text/csv'];
    $filename = 'template_kamus_normalisasi.csv';

    $callback = function () {
      $file = fopen('php://output', 'w');
      fputcsv($file, ['kata_tidak_baku', 'kata_baku']); // header
      fputcsv($file, ['dmkr', 'damkar']);
      fputcsv($file, ['tdk', 'tidak']);
      fputcsv($file, ['yg', 'yang']);
      fclose($file);
    };

    return response()->stream($callback, 200, array_merge($headers, [
      'Content-Disposition' => "attachment; filename={$filename}",
    ]));
  }
}