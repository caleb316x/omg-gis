<?php

namespace App\Http\Controllers;

use App\Models\Coordinate;
use App\Models\Plot;
use Illuminate\Http\Request;

class PlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mapview()
    {
        return view('plot.map');
    }

    public function index()
    {
        //
    }

    public function getPlots()
    {
        $plots = Plot::with("coordinates")->get();
        return response()->json($plots);
    }

    public function getGrave($id)
    {
        $plot = Plot::find($id);
        return response()->json($plot);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $plot = Plot::create($request->all());
        $request->shape_data = Coordinate::getMapCoordinates($request->shape_data,$plot);
        $coordinate = Coordinate::insert($request->shape_data);
        $plotData = Plot::with("coordinates")->whereId($plot->id)->get();
        return response()->json($plotData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plot  $plot
     * @return \Illuminate\Http\Response
     */
    public function show(Plot $plot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plot  $plot
     * @return \Illuminate\Http\Response
     */
    public function edit(Plot $plot)
    {
        //
    }

    public function graveUpdate(Request $request, $id){
        return $request;
        $plot = Plot::find($id);
        $plot->name = $request->name;
        $plot->user_id = $request->user_id;
        $plot->save();

        return $plot;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plot  $plot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plot $plot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plot  $plot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plot $plot)
    {
        //
    }
}
