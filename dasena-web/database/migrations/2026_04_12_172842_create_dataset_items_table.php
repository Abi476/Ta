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
    Schema::create('dataset_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('dataset_id')->constrained('datasets')->onDelete('cascade');
      $table->string('keyword')->nullable();
      $table->date('tanggal')->nullable();
      $table->text('teks');
      $table->text('teks_cleansed')->nullable();
      $table->text('teks_stopword')->nullable();
      $table->text('teks_stemmed')->nullable();
      $table->enum('sentimen', ['Positif', 'Netral', 'Negatif'])->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('dataset_items');
  }
};