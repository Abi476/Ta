<?php

namespace App\Exports;

use App\Models\DatasetItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HasilAnalisisExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
  public function query()
  {
    return DatasetItem::query()->whereNotNull('teks_stemmed');
  }

  public function headings(): array
  {
    return [
      'Tanggal',
      'Komentar Asli',
      'Teks Bersih (Hasil Preprocessing)',
      'Sentimen'
    ];
  }

  public function map($item): array
  {
    return [
      $item->created_at ? $item->created_at->format('d M Y') : '-',
      $item->teks,
      $item->teks_stemmed,
      $item->sentimen ?? 'Belum Dianalisis',
    ];
  }
}