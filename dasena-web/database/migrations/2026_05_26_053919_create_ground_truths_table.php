<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ground_truths', function (Blueprint $table) {
            $table->id();
            $table->text('teks_asli')->nullable(); 
            $table->text('teks_cleansed');
            $table->enum('sentimen', ['Positif', 'Netral', 'Negatif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ground_truths');
    }
};