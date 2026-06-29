<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatasetItem;
use Illuminate\Support\Facades\Http;
use App\Models\KamusNormalisasi;

class PreprocessingController extends Controller
{
  public function index(Request $request)
  {
    $query = DatasetItem::query();

    if ($request->has('search') && $request->search != '') {
      $query->where('teks', 'like', '%' . $request->search . '%');
    }

    $datasetItems = $query->orderBy('tanggal', 'asc')->paginate(15);
    $totalItems = DatasetItem::count();
    $processedItems = DatasetItem::whereNotNull('teks_stemmed')->count();

    return view('admin.preprocessing.index', compact(
      'datasetItems',
      'totalItems',
      'processedItems'
    ));
  }

  public function filterAndCleanData(Request $request)
  {
    try {
      $countAwal = \App\Models\DatasetItem::count();
      $allData = \App\Models\DatasetItem::orderBy('id')->get(['id', 'teks']);
      $uniqueCoreTexts = [];
      $idsToDelete = [];

      $noiseList = [
          'tribun', 'tribunnews', 'kompas', 'antara', 'antaranews', 'liputan6', 'kumparan', 'sindonews', 'merdeka', 'viva', 'inews', 'tvone', 'jawapos', 'radar\b', 'jprk', 'jp\s+radar', 'reporter', 'redaksi', 'wartawan', 'press\s+release', 'rilis', 'jurnalis', 'kontributor', 'editor', 'news', 'berita', 'artikel', 'website',
          'ujar\s+akun', 'ungkap\s+akun', 'tulis\s+akun', 'kata\s+kabid', 'kata\s+kepala', 'kata\s+pelaksana', 'menurut\s+informasi', 'menurut\s+warga', 'dalam\s+keterangannya', 'dimintai\s+konfirmasi', 'dikutip\s+dari', 'dilansir\s+dari', 'melansir', 'slide\s+liputan', 'liputan\s+on\s+tiktok', 'akun\s+instagram', 'lewat\s+dm\s+ke\s+akun',
          'repost', 'source', 'sc:', 'cr:', '\bref\.', 'vid\.', '\bsumber\s*:', '\bfoto\s*:', '\bfoto\s+[a-z0-9_]+\s*/', 'sumber\s+foto', 'selengkapnya\s+bisa\s+dibaca', 'baca\s+selengkapnya', 'klik\s+link', 'dibio', 'di\s+bio', 'linimasa',
          'feedgramindo', 'jangan\s+lupa\s+like', 'like\s+coment\s+share', 'like\s+comment\s+share', 'comment\s+share', 'coment\s+share', 'pengirim\s+stars\s+teratas', 'stars\s+teratas', 'biar\s+kite\s+makin\s+semangat',
          'loker', 'lowongan', 'rekrutmen', 'pelamar', 'pendaftaran\s+petugas', 'sscasn',
          'apel\s+pagi', 'apel\s+senin', 'apel\s+gabungan', 'simulasi\s+kebakaran',
          'interview', 'gue\s+hr', 'suamik', 'paksu', 'tukar\s+kado', 'kebakaran\s+jenggot', 'anime', 'jual\s+beli', 'jual\s+seragam', 'sewakan\s+seragam', 'bioskop', 'wattpad', 'novel', 'fiksi', 'sobat\s+polri', 'minwas', 'pasal', 'outing', 'rapor', 'raport', 'ambil\s+rapor', 'mengambil\s+rapor', 'pengambilan\s+rapor', 'suami\s+idaman', 'kerja\s+sambil\s+dengar\s+musik', 'me\s+my\s+journey', 'tenangbis', 'ucok\s+core', 'nilai\s+sendiri', 'ada\s+ada\s+aja',
          'horor', 'setan', 'makhluk\s+halus', 'ghoib', 'gaib', 'ritual', 'ruqyah', 'ular\s+gaib', 'kesurupan', 'menikahi', 'gadis\s+itu\s+berjanji',
          'los\s+angeles', 'california', 'amerika\s+serikat', 'wildfire', 'prayfor', 'jepang', 'amerika', 'china', 'korea', 'luar\s+negeri', 'tragedipemadamkebakaranlosangles',
          'inibalikpapanbosku', 'halo\b',
          'perhatikan\s+setiap\s+langkahmu', 'terima\s+kasih\s+atas\s+semua\s+dukungannya', 'kb\s+tadzkiya', 'anak\s+happy', 'diajak\s+keliling\s+kota', 'keliling\s+kota\s+bangun\s+dg\s+mobil\s+damkar', 'pesawat\s+pemadam\s+kebakaran\s+la'
      ];
      $noiseRegex = '#(' . implode('|', $noiseList) . ')#i';

      foreach ($allData as $item) {
          $text = strval($item->teks);
          $splitTranslate = preg_split('/\|?\s*(translate|ai info)/i', $text);
          $core = $splitTranslate[0];
          
          $parts = explode('|', $core);
          if (count($parts) > 2) {
              $core = implode('|', array_slice($parts, 2));
          }
          $core = trim($core);

          if (
              in_array($core, $uniqueCoreTexts, true) || 
              !preg_match('/\b(damkar|pemadam|kebakaran|gulkarmat|dpkp)\b/i', $text) ||
              preg_match($noiseRegex, $text)
          ) {
              $idsToDelete[] = $item->id;
          } else {
              $uniqueCoreTexts[] = $core;
          }
      }

      // Hapus data secara bertahap
      if (count($idsToDelete) > 0) {
          foreach (array_chunk($idsToDelete, 1000) as $chunk) {
              \App\Models\DatasetItem::whereIn('id', $chunk)->delete();
          }
      }

      $datasets = \App\Models\Dataset::all();
      foreach ($datasets as $dataset) {
        $actualCount = \App\Models\DatasetItem::where('dataset_id', $dataset->id)->count();
        $dataset->update(['total_rows' => $actualCount]);
      }

      $deletedTotal = count($idsToDelete);

      if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
          'success' => true,
          'sampah' => $deletedTotal
        ]);
      }

      return back()
        ->with('success', 'Filtering Ekstrem Selesai!')
        ->with('irrelevant_terhapus', $deletedTotal);

    } catch (\Exception $e) {
      if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
      }
      return back()->with('error', 'Gagal memfilter data: ' . $e->getMessage());
    }
  }

  public function process()
  {
    $items = DatasetItem::whereNull('teks_stemmed')->take(50)->get();

    if ($items->isEmpty()) {
      return response()->json(['status' => 'completed']);
    }

    $kamus = KamusNormalisasi::pluck('kata_baku', 'kata_tidak_baku')->toArray();
    $payload = [];
    $dataUpdateLokal = [];

    // Ambil histori seluruh case_folding unik dari database 
    $existingCleansed = DatasetItem::whereNotNull('teks_cleansed')->pluck('teks_cleansed')->toArray();
    $lokalCleansedCheck = $existingCleansed;

    $noiseList = [
        'tribun', 'tribunnews', 'kompas', 'antara', 'antaranews', 'liputan6', 'kumparan', 'sindonews', 'merdeka', 'viva', 'inews', 'tvone', 'jawapos', 'radar\b', 'jprk', 'jp\s+radar', 'reporter', 'redaksi', 'wartawan', 'press\s+release', 'rilis', 'jurnalis', 'kontributor', 'editor', 'news', 'berita', 'artikel', 'website',
        'ujar\s+akun', 'ungkap\s+akun', 'tulis\s+akun', 'kata\s+kabid', 'kata\s+kepala', 'kata\s+pelaksana', 'menurut\s+informasi', 'menurut\s+warga', 'dalam\s+keterangannya', 'dimintai\s+konfirmasi', 'dikutip\s+dari', 'dilansir\s+dari', 'melansir', 'slide\s+liputan', 'liputan\s+on\s+tiktok', 'akun\s+instagram', 'lewat\s+dm\s+ke\s+akun',
        'repost', 'source', 'sc:', 'cr:', '\bref\.', 'vid\.', '\bsumber\s*:', '\bfoto\s*:', '\bfoto\s+[a-z0-9_]+\s*/', 'sumber\s+foto', 'selengkapnya\s+bisa\s+dibaca', 'baca\s+selengkapnya', 'klik\s+link', 'dibio', 'di\s+bio', 'linimasa',
        'feedgramindo', 'jangan\s+lupa\s+like', 'like\s+coment\s+share', 'like\s+comment\s+share', 'comment\s+share', 'coment\s+share', 'pengirim\s+stars\s+teratas', 'stars\s+teratas', 'biar\s+kite\s+makin\s+semangat',
        'loker', 'lowongan', 'rekrutmen', 'pelamar', 'pendaftaran\s+petugas', 'sscasn',
        'apel\s+pagi', 'apel\s+senin', 'apel\s+gabungan', 'simulasi\s+kebakaran',
        'interview', 'gue\s+hr', 'suamik', 'paksu', 'tukar\s+kado', 'kebakaran\s+jenggot', 'anime', 'jual\s+beli', 'jual\s+seragam', 'sewakan\s+seragam', 'bioskop', 'wattpad', 'novel', 'fiksi', 'sobat\s+polri', 'minwas', 'pasal', 'outing', 'rapor', 'raport', 'ambil\s+rapor', 'mengambil\s+rapor', 'pengambilan\s+rapor', 'suami\s+idaman', 'kerja\s+sambil\s+dengar\s+musik', 'me\s+my\s+journey', 'tenangbis', 'ucok\s+core', 'nilai\s+sendiri', 'ada\s+ada\s+aja',
        'horor', 'setan', 'makhluk\s+halus', 'ghoib', 'gaib', 'ritual', 'ruqyah', 'ular\s+gaib', 'kesurupan', 'menikahi', 'gadis\s+itu\s+berjanji',
        'los\s+angeles', 'california', 'amerika\s+serikat', 'wildfire', 'prayfor', 'jepang', 'amerika', 'china', 'korea', 'luar\s+negeri', 'tragedipemadamkebakaranlosangles',
        'inibalikpapanbosku', 'halo\b',
        'perhatikan\s+setiap\s+langkahmu', 'terima\s+kasih\s+atas\s+semua\s+dukungannya', 'kb\s+tadzkiya', 'anak\s+happy', 'diajak\s+keliling\s+kota', 'keliling\s+kota\s+bangun\s+dg\s+mobil\s+damkar', 'pesawat\s+pemadam\s+kebakaran\s+la'
    ];
    $noiseRegex = '#(' . implode('|', $noiseList) . ')#i';

    foreach ($items as $item) {
      $text = strval($item->teks);
      
      // Cleansing Dasar
      $splitTranslate = preg_split('/\|?\s*(translate|ai info)/i', $text);
      $text = $splitTranslate[0];

      if (strpos($text, '|') !== false) {
          $parts = explode('|', $text);
          if (count($parts) > 2) {
              $contentParts = array_slice($parts, 2);
              $validParts = [];
              foreach ($contentParts as $p) {
                  $pClean = trim($p);
                  if (preg_match('/^[\d\.,]+[KkMmBb]?$/i', $pClean)) continue;
                  if ($pClean === '.' || $pClean === '') continue;
                  $validParts[] = $pClean;
              }
              $text = implode(' ', $validParts);
          }
      }

      $text = preg_replace('/\breplying to\b/i', '', $text);
      $text = preg_replace('/@[\w_.]*damkar[\w_.]*/i', ' damkar ', $text);
      $text = preg_replace('/@[A-Za-z0-9_.]+/i', '', $text);
      $text = preg_replace('/#.*/', '', $text);
      $text = preg_replace('/[🎥📸].*/u', '', $text);
      $text = preg_replace('/(http\S+|www\S+|https\S+)/i', '', $text);
      $text = preg_replace('/\b(video|vid|foto|poto|credit|credits|source|sumber|sc|cr)\s*[:\/]\s*.*/i', '', $text);
      $text = preg_replace('/\b(ig|instagram|tiktok|youtube)\s*[:\/]\s*\S+.*/i', '', $text);
      $text = preg_replace('/\b(report by|story ig|baca selengkapnya|selengkapnya|klik link|di bio|dibio)\b.*/i', '', $text);
      $text = preg_replace('/\b\w+\.(go\.id|co\.id|ac\.id|or\.id|web\.id|com|id|net|org)(?:\/\S*)?.*/i', '', $text);
      $text = preg_replace('/[^a-zA-Z\s]/', ' ', $text);
      $teksCleansed = strtolower($text);

      // Filtering Ekor Kalimat & Duplikat Bertingkat
      $teksCleansed = preg_replace('/\b(sc|cr|source|sumber|credit|credits)\b.*$/i', '', $teksCleansed);
      $teksCleansed = preg_replace('/\b(foto|poto)\s+(bpk|ibu|pak|by|dari|dok|dokumentasi|akun|ig|instagram|tiktok|youtube)?\b.*$/i', '', $teksCleansed);
      $teksCleansed = trim(preg_replace('/\s+/', ' ', $teksCleansed));

      //Cek Duplikat Array
      if (in_array($teksCleansed, $lokalCleansedCheck, true)) {
          DatasetItem::where('id', $item->id)->delete();
          continue; 
      }
      $lokalCleansedCheck[] = $teksCleansed;

      // Cek Noise
      if (preg_match($noiseRegex, $teksCleansed) || $teksCleansed === "") {
          DatasetItem::where('id', $item->id)->delete();
          continue;
      }

      //Pengecekan < 4 kata (spasi array)
      $wordsArray = array_values(array_filter(explode(' ', $teksCleansed)));
      $wordCount = count($wordsArray);
      
      if ($wordCount < 4) {
          DatasetItem::where('id', $item->id)->delete();
          continue; 
      }

      //memastikan mengandung kata kunci damkar di teks akhir
      if (!preg_match('/\b(damkar|pemadam|kebakaran|gulkarmat|dpkp)\b/i', $teksCleansed)) {
          DatasetItem::where('id', $item->id)->delete();
          continue; 
      }

      // Normalisasi
      $tokenisasi = json_encode($wordsArray);
      $normalizedWords = array_map(function ($word) use ($kamus) {
        return $kamus[$word] ?? $word;
      }, $wordsArray);
      $teksNormalized = implode(' ', $normalizedWords);

      $payload[] = [
        'id' => $item->id,
        'teks' => $teksNormalized
      ];

      $dataUpdateLokal[$item->id] = [
        'word_count' => $wordCount,
        'teks_cleansed' => $teksCleansed,
        'tokenisasi' => $tokenisasi,
        'teks_normalized' => $teksNormalized
      ];
    }

    if (empty($payload)) {
        return response()->json(['status' => 'processing', 'processed' => DatasetItem::whereNotNull('teks_stemmed')->count(), 'total' => DatasetItem::count()]);
    }

    try {
      $flaskUrl = env('FLASK_API_URL', 'http://127.0.0.1:5000') . '/api/preprocess';
      $response = Http::timeout(300)->post($flaskUrl, ['data' => $payload]);

      if ($response->successful()) {
        $results = $response->json('data');

        foreach ($results as $res) {
          $id = $res['id'];
          DatasetItem::where('id', $id)->update([
            'word_count' => $dataUpdateLokal[$id]['word_count'],
            'teks_cleansed' => $dataUpdateLokal[$id]['teks_cleansed'],
            'tokenisasi' => $dataUpdateLokal[$id]['tokenisasi'],
            'teks_normalized' => $dataUpdateLokal[$id]['teks_normalized'],
            'teks_stopword' => $res['teks_stopword'],
            'teks_stemmed' => $res['teks_stemmed']
          ]);
        }

        $sisa = DatasetItem::whereNull('teks_stemmed')->count();
        $total = DatasetItem::count();
        $processed = $total - $sisa;

        return response()->json(['status' => 'processing', 'processed' => $processed, 'total' => $total]);
      }

      return response()->json(['status' => 'error', 'message' => 'API Flask error.'], 500);

    } catch (\Exception $e) {
      return response()->json(['status' => 'error', 'message' => 'Gagal koneksi Flask: ' . $e->getMessage()], 500);
    }
  }
}