<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as TheModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends TheModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}
