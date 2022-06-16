<?php

namespace App\Models;

use Faker\Core\Coordinates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['plot_id','lat','long','shape_type'];

    public static function getMapCoordinates($coordinates,$plot){
        $coordinates_data = [];
        foreach ($coordinates["coordinates"] as $c){
            $c_data = array(
            'plot_id' => $plot->id,
            'lat' => $c['lat'],
            'long' => $c['lng'],
            'shape_type' => $coordinates['type']);
            array_push($coordinates_data,$c_data);
        }

        return $coordinates_data;
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }
}
