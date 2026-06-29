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
    Schema::create('datasets', function (Blueprint $table) {
      $table->id();
      $table->string('file_name');
      $table->string('batch_name')->nullable();
      $table->string('file_path');
      $table->integer('total_rows')->default(0);
      $table->enum('status', ['Pending', 'Processing', 'Selesai Diproses'])->default('Pending');
      $table->timestamps(); // created_at = Tanggal Unggah
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('datasets');
  }
};
