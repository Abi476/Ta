<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dataset extends Model
{
  protected $fillable = ['file_name', 'batch_name', 'file_path', 'total_rows', 'status'];

  public function items(): HasMany
  {
    return $this->hasMany(DatasetItem::class);
  }
}