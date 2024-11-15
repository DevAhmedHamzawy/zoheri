<?php

namespace App\Models;

use App\Models\Model as ModelsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function hurdle()
    {
        return $this->belongsTo(Hurdle::class);
    }

    public function model()
    {
        return $this->belongsTo(ModelsModel::class);
    }
}
