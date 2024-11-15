<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


}
