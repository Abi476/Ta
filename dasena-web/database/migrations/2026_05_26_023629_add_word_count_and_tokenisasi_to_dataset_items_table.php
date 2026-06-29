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
    Schema::table('dataset_items', function (Blueprint $table) {
      $table->unsignedInteger('word_count')->nullable()->after('teks');
      $table->text('tokenisasi')->nullable()->after('teks_cleansed');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('dataset_items', function (Blueprint $table) {
      $table->dropColumn(['word_count', 'tokenisasi']);
    });
  }
};