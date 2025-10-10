<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evidence extends Model
{
    use HasFactory;

    protected $table = 'evidences';

    protected $fillable = [
        'user_id',
        'lokasi',
        'deskripsi',
        'file_path',
        'status',
        'catatan_admin',
    ];

    /**
     * The attributes that should be cast to native types.
     * Ini adalah bagian paling penting untuk masalah 'file 0'.
     */
    protected $casts = [
        'file_path' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}