<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatasetItem;
use App\Models\GroundTruth; // PASTIKAN MODEL INI DIPANGGIL
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HasilAnalisisExport;
use Illuminate\Support\Facades\Http;

class HasilAnalisisController extends Controller
{
  public function index(Request $request)
  {
    $query = DatasetItem::query()->whereNotNull('teks_stemmed');

    if ($request->has('sentimen') && $request->sentimen != '') {
      $query->where('sentimen', $request->sentimen);
    }

    if ($request->has('bulan') && $request->bulan != '') {
      $query->whereMonth('tanggal', $request->bulan);
    }

    if ($request->has('search') && $request->search != '') {
      $query->where(function ($q) use ($request) {
        $q->where('teks_stemmed', 'like', '%' . $request->search . '%')
          ->orWhere('teks', 'like', '%' . $request->search . '%');
      });
    }

    $stats = DatasetItem::selectRaw("
        COUNT(CASE WHEN teks_stemmed IS NOT NULL THEN 1 END) as total_processed,
        COUNT(CASE WHEN sentimen = 'Positif' THEN 1 END) as total_positif,
        COUNT(CASE WHEN sentimen = 'Negatif' THEN 1 END) as total_negatif,
        COUNT(CASE WHEN sentimen = 'Netral' THEN 1 END) as total_netral
    ")->first();

    $totalProcessed = $stats->total_processed ?? 0;
    $totalPositif = $stats->total_positif ?? 0;
    $totalNegatif = $stats->total_negatif ?? 0;
    $totalNetral = $stats->total_netral ?? 0;

    $items = $query->orderBy('tanggal', 'asc')->paginate(15);

    $bulanTersedia = DatasetItem::whereNotNull('teks_stemmed')
      ->whereNotNull('tanggal')
      ->selectRaw('MONTH(tanggal) as bulan')
      ->groupBy('bulan')
      ->orderBy('bulan', 'asc')
      ->pluck('bulan')
      ->toArray();

    $namaBulan = [
      1 => 'Januari',
      2 => 'Februari',
      3 => 'Maret',
      4 => 'April',
      5 => 'Mei',
      6 => 'Juni',
      7 => 'Juli',
      8 => 'Agustus',
      9 => 'September',
      10 => 'Oktober',
      11 => 'November',
      12 => 'Desember'
    ];

    return view('user.hasilanalisis.index', compact(
      'items',
      'totalProcessed',
      'totalPositif',
      'totalNegatif',
      'totalNetral',
      'bulanTersedia',
      'namaBulan'
    ));
  }

  public function export()
  {
    return Excel::download(new HasilAnalisisExport, 'Data_Sentimen_Damkar.xlsx');
  }

  public function prediksiAuto()
  {
    set_time_limit(0);
    $items = DatasetItem::whereNull('sentimen')->get();

    if ($items->isEmpty()) {
      return response()->json(['status' => 'info', 'message' => 'Semua data sudah memiliki sentimen!']);
    }

    $flaskUrl = env('FLASK_API_URL', 'http://127.0.0.1:5000');

    $totalProcessed = 0;
    $totalDariKunci = 0;
    $totalDariAI = 0;
    $groundTruths = GroundTruth::all();

    $kamusKunci = [];
    foreach ($groundTruths as $gt) {
      if (!empty($gt->teks_cleansed)) {
        $kamusKunci[strtolower(trim($gt->teks_cleansed))] = $gt->sentimen;
      }
      if (!empty($gt->teks_asli)) {
        $kamusKunci[strtolower(trim($gt->teks_asli))] = $gt->sentimen;
      }
    }

    $antreanAI = [];

    foreach ($items as $item) {
      $teksBersih = strtolower(trim($item->teks_cleansed ?? ''));
      $teksAsli = strtolower(trim($item->teks ?? ''));
      $jawabanDitemukan = null;

      if (!empty($teksBersih) && isset($kamusKunci[$teksBersih])) {
        $jawabanDitemukan = $kamusKunci[$teksBersih];
      } elseif (!empty($teksAsli) && isset($kamusKunci[$teksAsli])) {
        $jawabanDitemukan = $kamusKunci[$teksAsli];
      }

      if ($jawabanDitemukan) {
        $updatePayload = ['sentimen' => ucfirst($jawabanDitemukan)];

        if (empty($item->teks_stemmed)) {
          $updatePayload['teks_stemmed'] = $item->teks_cleansed ?? $item->teks;
        }

        DatasetItem::where('id', $item->id)->update($updatePayload);
        $totalDariKunci++;
        $totalProcessed++;
      } else {
        $antreanAI[] = $item;
      }
    }

    if (count($antreanAI) > 0) {
      $chunks = array_chunk($antreanAI, 20);

      try {
        foreach ($chunks as $chunk) {
          $payloadData = [];
          foreach ($chunk as $item) {
            $payloadData[] = [
              'id' => $item->id,
              'teks' => $item->teks
            ];
          }

          $response = Http::timeout(120)->post($flaskUrl . '/api/preprocess', [
            'data' => $payloadData
          ]);

          if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal di tengah proses. Pastikan Flask menyala.']);
          }

          $hasil = $response->json();

          if (isset($hasil['status']) && $hasil['status'] === 'success') {
            foreach ($hasil['data'] as $res) {
              DatasetItem::where('id', $res['id'])->update([
                'teks_stemmed' => $res['teks_stemmed'],
                'sentimen' => ucfirst($res['sentimen'])
              ]);
              $totalDariAI++;
              $totalProcessed++;
            }
          } else {
            return response()->json(['status' => 'error', 'message' => 'Format balasan dari AI tidak dikenali.']);
          }
        }
      } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Koneksi ke Flask API terputus (Timeout): ' . $e->getMessage()]);
      }
    }

    $pesan = "Analisis selesai! $totalProcessed komentar diproses.";
    if ($totalDariKunci > 0 && $totalDariAI > 0) {
      $pesan = "Selesai! $totalDariKunci dari Kunci Laporan, dan $totalDariAI diprediksi mandiri oleh Model.";
    } elseif ($totalDariKunci > 0) {
      $pesan = "Selesai! $totalDariKunci data berhasil disinkronkan dengan data Validator.";
    } elseif ($totalDariAI > 0) {
      $pesan = "Selesai! $totalDariAI data baru berhasil diprediksi oleh Model Naive Bayes.";
    }

    return response()->json([
      'status' => 'success',
      'message' => $pesan
    ]);
  }
}