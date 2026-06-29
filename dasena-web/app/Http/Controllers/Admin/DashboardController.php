<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KamusNormalisasi;
use App\Models\DatasetItem;
use App\Models\Dataset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
  public function index()
  {
    $stats = [
      'positif' => DatasetItem::where('sentimen', 'Positif')->count(),
      'netral' => DatasetItem::where('sentimen', 'Netral')->count(),
      'negatif' => DatasetItem::where('sentimen', 'Negatif')->count(),
      'total' => DatasetItem::whereNotNull('sentimen')->count(),
    ];

    $trendData = DatasetItem::selectRaw('MONTH(tanggal) as bulan, sentimen, count(*) as jumlah')
      ->whereNotNull('sentimen')
      ->whereNotNull('tanggal')
      ->whereYear('tanggal', '2025')
      ->groupBy('bulan', 'sentimen')
      ->get();

    $recentComments = DatasetItem::orderBy('tanggal', 'desc')->take(5)->get();

    $getWordCloudData = function ($sentimen) {
      $texts = DatasetItem::where('sentimen', $sentimen)->pluck('teks_stemmed')->toArray();
      $wordCounts = [];
      foreach ($texts as $text) {
        $words = explode(' ', strtolower($text));
        foreach ($words as $word) {
          $word = trim($word);
          if (strlen($word) > 2) {
            if (!isset($wordCounts[$word])) {
              $wordCounts[$word] = 0;
            }
            $wordCounts[$word]++;
          }
        }
      }
      arsort($wordCounts);
      $topWords = array_slice($wordCounts, 0, 50);
      $wordCloudData = [];
      foreach ($topWords as $word => $count) {
        $wordCloudData[] = ['x' => $word, 'value' => $count];
      }
      return $wordCloudData;
    };

    $wordCloudDataPositif = $getWordCloudData('Positif');
    $wordCloudDataNetral = $getWordCloudData('Netral');
    $wordCloudDataNegatif = $getWordCloudData('Negatif');

    $flaskStatus = false;
    try {
      Http::timeout(2)->send('OPTIONS', env('FLASK_API_URL', 'http://127.0.0.1:5000') . '/api/preprocess');
      $flaskStatus = true;
    } catch (\Exception $e) {
      $flaskStatus = false;
    }

    $adminData = [
      'total_users' => User::count(),
      'total_kamus' => KamusNormalisasi::count(),
      'pending_prep' => DatasetItem::whereNull('sentimen')->count(),
      'total_all_data' => DatasetItem::count(),
      'flask_online' => $flaskStatus,
      'latest_datasets' => Dataset::latest()->take(3)->get(),
      'prediksi_hari_ini' => DatasetItem::whereDate('updated_at', today())
        ->whereNotNull('sentimen')
        ->count(),
    ];

    return view('admin.dashboard.index', compact(
      'stats',
      'trendData',
      'recentComments',
      'adminData',
      'wordCloudDataPositif',
      'wordCloudDataNetral',
      'wordCloudDataNegatif'
    ));
  }
}