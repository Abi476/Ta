<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dataset;
use App\Models\DatasetItem;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TemplateExport;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UploadController extends Controller
{
  public function index()
  {
    $datasets = Dataset::latest()->get();
    return view('admin.upfile.index', compact('datasets'));
  }

  public function process(Request $request)
  {
    $request->validate([
      'file_dataset' => 'required|mimes:csv,xlsx,xls|max:10240',
      'batch_name' => 'nullable|string|max:255',
    ]);

    if ($request->hasFile('file_dataset')) {
      $file = $request->file('file_dataset');
      $fileName = time() . '_' . $file->getClientOriginalName();
      $path = $file->storeAs('public/datasets', $fileName);
      $dataArray = Excel::toArray([], $file);
      $rows = $dataArray[0];
      array_shift($rows);

      $dataset = Dataset::create([
        'file_name' => $file->getClientOriginalName(),
        'batch_name' => $request->batch_name,
        'file_path' => $path,
        'total_rows' => count($rows),
        'status' => 'Pending',
      ]);

      foreach ($rows as $row) {
        if (isset($row[2]) && trim($row[2]) !== '') {
          $tanggal = null;
          if (isset($row[1])) {
            if (is_numeric($row[1])) {
              try {
                $tanggal = Date::excelToDateTimeObject($row[1])->format('Y-m-d');
              } catch (\Throwable $e) {
                $tanggal = null;
              }
            } else {
              try {
                $tanggal = \Carbon\Carbon::parse($row[1])->format('Y-m-d');
              } catch (\Throwable $e) {
                $tanggal = date('Y-m-d', strtotime($row[1]));
              }
            }
          }

          DatasetItem::create([
            'dataset_id' => $dataset->id,
            'keyword' => $row[0] ?? null,
            'tanggal' => $tanggal,
            'teks' => $row[2],
          ]);
        }
      }
      $dataset->update([
        'status' => 'Selesai Diproses'
      ]);

      return redirect()->back()->with('success', 'Berhasil! Dataset telah diurai dan disimpan ke database.');
    }

    return redirect()->back()->with('error', 'Gagal memproses file.');
  }

  public function downloadTemplate()
  {
    return Excel::download(new TemplateExport, 'template_dasena.xlsx');
  }

  public function destroy($id)
  {
    try {
      $dataset = Dataset::findOrFail($id);

      if (Storage::exists($dataset->file_path)) {
        Storage::delete($dataset->file_path);
      }
      DatasetItem::where('dataset_id', $dataset->id)->delete();

      $dataset->delete();

      return response()->json([
        'success' => true,
        'message' => 'Data beserta seluruh baris komentar berhasil dihapus.'
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Gagal menghapus: ' . $e->getMessage()
      ], 500);
    }
  }
}