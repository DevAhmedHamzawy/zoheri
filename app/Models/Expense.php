<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function getImgPathAttribute()
    {
        return url('storage/main/expenses/'.$this->image);
    }
}
