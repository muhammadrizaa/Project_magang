<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Evidence extends Model
{
    protected $table = 'evidences';
    
    protected $guarded = [];

    protected $casts = [
        'file_path' => 'array',
    ];

    public function assignment() {
        return $this->belongsTo(Assignment::class);
    }
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }
    public function waspang() {
        return $this->belongsTo(Waspang::class);
    }
    public function tematik() {
        return $this->belongsTo(Tematik::class);
    }
    public function report() {
        return $this->hasOne(Report::class);
    }
}