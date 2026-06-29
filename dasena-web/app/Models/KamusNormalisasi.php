<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamusNormalisasi extends Model
{
  use HasFactory;

  protected $fillable = [
    'kata_tidak_baku',
    'kata_baku'
  ];
}