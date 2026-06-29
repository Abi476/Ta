<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatasetItem;

class DashboardController extends Controller
{
  public function index()
  {
    if (auth()->check() && auth()->user()->role === 'admin') {
      return redirect()->route('admin.dashboard');
    }

    $stats = [
      'positif' => DatasetItem::where('sentimen', 'Positif')->count(),
      'netral' => DatasetItem::where('sentimen', 'Netral')->count(),
      'negatif' => DatasetItem::where('sentimen', 'Negatif')->count(),
      'total' => DatasetItem::whereNotNull('sentimen')->count(),
    ];

    $totalAllData = DatasetItem::count();

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

    return view('user.dashboard.index', compact(
      'stats',
      'trendData',
      'recentComments',
      'totalAllData',
      'wordCloudDataPositif',
      'wordCloudDataNetral',
      'wordCloudDataNegatif'
    ));
  }
}