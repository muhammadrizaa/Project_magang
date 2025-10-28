<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evidence extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'evidences';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'lokasi',
        'deskripsi',
        'file_path',
        'status',
        'catatan_admin',
    ];

    /**
     * Cast kolom tertentu ke tipe data native.
     * Penting agar file_path yang disimpan dalam format JSON di database
     * bisa otomatis dikonversi menjadi array ketika diambil.
     */
    protected $casts = [
        'file_path' => 'array',
    ];

    /**
     * Relasi ke tabel users.
     * Setiap evidence dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
