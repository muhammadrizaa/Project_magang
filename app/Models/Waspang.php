<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waspang extends Model
{
    protected $table = 'waspang';
    protected $fillable = ['nama_waspang', 'nik_waspang'];

    public function evidence()
    {
        return $this->hasMany(Evidence::class);
    }
}