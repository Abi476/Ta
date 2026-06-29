<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('kamus_normalisasis', function (Blueprint $table) {
      $table->id();
      $table->string('kata_tidak_baku')->unique();
      $table->string('kata_baku');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('kamus_normalisasis');
  }
};
