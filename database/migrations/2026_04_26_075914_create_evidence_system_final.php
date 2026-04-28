<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    // Tabel Master
    Schema::create('waspang', function (Blueprint $table) {
        $table->id();
        $table->string('nama_waspang');
        $table->string('nik_waspang')->nullable();
        $table->timestamps();
    });

    Schema::create('tematik', function (Blueprint $table) {
        $table->id();
        $table->string('nama_tematik');
        $table->timestamps();
    });

    Schema::create('project', function (Blueprint $table) {
        $table->id();
        $table->text('lokasi');
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });

    Schema::create('mapping', function (Blueprint $table) {
        $table->id();
        $table->string('nama_area');
        $table->string('kode_mapping')->unique();
        $table->timestamps();
    });

    Schema::create('purchase_order', function (Blueprint $table) {
        $table->id();
        $table->string('no_po')->unique();
        $table->timestamps();
    });

    // Tabel Transaksi (Butuh Users & Master)
    Schema::create('assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('project_id')->constrained('project')->onDelete('cascade');
        $table->foreignId('mapping_id')->constrained('mapping')->onDelete('cascade');
        $table->date('tgl_penugasan')->nullable();
        $table->enum('status_tugas', ['aktif', 'selesai'])->default('aktif');
        $table->timestamps();
    });

    Schema::create('evidences', function (Blueprint $table) {
        $table->id();
        $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
        $table->foreignId('po_id')->constrained('purchase_order');
        $table->foreignId('waspang_id')->constrained('waspang');
        $table->foreignId('tematik_id')->constrained('tematik');
        $table->text('file_path');
        $table->text('deskripsi')->nullable();
        $table->enum('status_laporan', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
    });

    Schema::create('approval_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('evidence_id')->constrained('evidences')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->text('catatan')->nullable();
        $table->string('status_log', 50);
        $table->timestamp('tgl_approval')->useCurrent();
    });

    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('evidence_id')->constrained('evidences')->onDelete('cascade');
        $table->string('no_laporan', 100)->nullable();
        $table->string('file_pdf')->nullable();
        $table->string('file_word')->nullable();
        $table->timestamp('tgl_generate')->useCurrent();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence_system_final');
    }
};
