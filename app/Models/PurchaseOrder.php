<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'purchase_order';

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = ['no_po'];

    /**
     * Relasi ke tabel evidence.
     * Satu PO memiliki banyak evidence (One-to-Many).
     */
    public function evidence(): HasMany
    {
        return $this->hasMany(Evidence::class, 'po_id');
    }
}