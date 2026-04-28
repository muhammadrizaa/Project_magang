<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project'; // Pakai 'project' sesuai nama tabel di migrasi
    protected $guarded = [];
}