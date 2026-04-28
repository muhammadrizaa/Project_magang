<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Import relasi
use App\Models\User;
use App\Models\Project;
use App\Models\Mapping;
use App\Models\Evidence;

class Assignment extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function mapping() {
        return $this->belongsTo(Mapping::class);
    }

    public function evidences() {
        return $this->hasMany(Evidence::class);
    }
}