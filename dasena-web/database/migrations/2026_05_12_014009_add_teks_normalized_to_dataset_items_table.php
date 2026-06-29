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
      $table->text('teks_normalized')->nullable()->after('teks_cleansed');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('dataset_items', function (Blueprint $table) {
      $table->dropColumn('teks_normalized');
    });
  }
};
