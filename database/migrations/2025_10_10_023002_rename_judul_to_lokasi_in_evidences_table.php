<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evidences', function (Blueprint $table) {
            // Mengganti nama kolom 'judul' menjadi 'lokasi'
            $table->renameColumn('judul', 'lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('evidences', function (Blueprint $table) {
            // Ini untuk jaga-jaga jika perlu kembali
            $table->renameColumn('lokasi', 'judul');
        });
    }
};