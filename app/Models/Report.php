<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    public function evidence() {
        return $this->belongsTo(Evidence::class);
    }
}