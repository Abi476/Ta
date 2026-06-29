<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UjiKlasifikasiController extends Controller
{
  public function index()
  {
    return view('user.ujiklasifikasi.index');
  }

  public function analisis(Request $request)
  {
    $request->validate([
      'teks' => 'required|string|min:3|max:1000',
    ], [
      'teks.required' => 'Teks tidak boleh kosong.',
      'teks.min' => 'Teks terlalu pendek, minimal 3 karakter.',
      'teks.max' => 'Teks terlalu panjang, maksimal 1000 karakter.',
    ]);

    try {

      $flaskUrl = env('FLASK_API_URL', 'http://127.0.0.1:5000');
      $response = Http::timeout(30)->post($flaskUrl . '/api/preprocess', [
        'data' => [
          [
            'id' => 1,
            'teks' => $request->teks,
          ]
        ]
      ]);

      if ($response->failed()) {
        return response()->json([
          'success' => false,
          'message' => 'Model AI tidak dapat dihubungi. Pastikan server Flask sedang berjalan.',
        ], 503);
      }

      $hasil = $response->json();

      if (isset($hasil['status']) && $hasil['status'] === 'success' && !empty($hasil['data'])) {
        $dataPrediksi = $hasil['data'][0];

        return response()->json([
          'success' => true,
          'sentimen' => ucfirst($dataPrediksi['sentimen']),
          'teks_stemmed' => $dataPrediksi['teks_stemmed'],
          'confidences' => $dataPrediksi['confidences'] ?? ['Positif' => 0, 'Netral' => 0, 'Negatif' => 0]
        ]);
      }

      return response()->json([
        'success' => false,
        'message' => 'Gagal memproses data di AI.',
      ], 500);

    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Koneksi ke Flask API gagal: ' . $e->getMessage(),
      ], 503);
    }
  }
}