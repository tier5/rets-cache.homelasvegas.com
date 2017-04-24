<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DB;
use App\PropertyDetail;
use App\PropertyImage;
use App\PropertyAdditional;
use App\PropertyExternalFeature;
use App\PropertyFeature;
use App\PropertyFinancialDetail;
use App\PropertyInteriorFeature;
use App\PropertyLocation;
use App\PropertyLatLong;
use App\Citylist;
use App\Jobs\InserSearchList;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd('hiiiii');
        //dd($request->all());
        //$term=
        $termcity=$request['city'];
        //dd($termcity);
        $termlisting_id=$request['listing_id'];
        //dd($termcity.''.$termlisting_id);
        $PropertyLocation = PropertyDetail:: where('City', '=', $termcity)->orWhere('MLSNumber','=',$termlisting_id)
    ->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->get(); 
        //dd($PropertyLocation);
        return response()->json($PropertyLocation);


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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
