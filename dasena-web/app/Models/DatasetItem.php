<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatasetItem extends Model
{
  protected $fillable = [
    'dataset_id',
    'keyword',
    'tanggal',
    'teks',
    'teks_cleansed',
    'teks_stopword',
    'teks_stemmed',
    'sentimen',
  ];
  
  public function dataset(): BelongsTo
  {
    return $this->belongsTo(Dataset::class);
  }
}