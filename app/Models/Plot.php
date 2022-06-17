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

    // public function user()
    // {
    //     return $this->has(User::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
