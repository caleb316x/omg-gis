<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
    use HasFactory;

    protected $fillable = ['type','price','area_length', 'area_width','block','status'];


    public function coordinates()
    {
        return $this->hasMany(Coordinate::class);
    }
}
