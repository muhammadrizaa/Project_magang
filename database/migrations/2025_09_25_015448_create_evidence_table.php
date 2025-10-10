<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_evidences_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->string('lokasi');
            $table->text('deskripsi')->nullable();
            $table->string('file_path'); // Path untuk menyimpan file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidences');
    }
};