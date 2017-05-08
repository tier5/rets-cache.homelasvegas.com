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
        $term=$request['search_input'];
        //dd($term);
        $myArray ="";
        $postal_code="";
        $community="";

        $termcity=$request['city'];
        //dd($termcity);
        $termlisting_id=$request['listing_id'];
        //dd($termcity.''.$termlisting_id);
        $termlisting_address=$request['address'];
        $termlisting_postal_code=$request['postal_code'];
        if($termlisting_postal_code!=""){
          $postal_code=$termlisting_postal_code;
        }
        $termlisting_search_community=$request['search-community'];
        if ($termlisting_search_community!=""){
            $community=$termlisting_search_community;
        }
        //dd($termlisting_address);
        if ($termlisting_address != ""){
        $myArray = explode(',', $termlisting_address);
        //dd($myArray);
        $termstreet_no=$myArray[0];        
        $termstreet_name=$myArray[1];
        //dd($termstreet_name);
        }
        else{
          $termstreet_no="";  
          $termstreet_name="";
        }
        if(isset($request['result_per_page']) && $request['result_per_page'] != "")
          {
            $limit=$request['result_per_page'];
            //dd($limit);
          }
        else
          {
            $limit=5;
          }
        if(isset($offset))
          {
            $start_page=($offset-1)*$limit;
          }
        else
          {
            $offset=0;
          }
        //dd($term);
        //dd($myArray[0]);
        //dd($termcity.'$'.$termlisting_address);
       
        //$PropertyLocation = PropertyDetail:: where('City', '=', $termcity)->orWhere('MLSNumber','=',$termlisting_id)->orWhere('StreetNumber','=',$termstreet_no)->orWhere('StreetName','=',$termstreet_name)->orWhereHas('propertylocation', function ($query) use ($community) {$query->where('CommunityName', '=',$community);})
    //->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->toSql(); 
        //dd($PropertyLocation);
          $PropertyLocation_full = PropertyDetail:: where('City', '=',$termcity)->orWhere('MLSNumber','=',$term)->orWhere('StreetNumber','like','%'.$term.'%')->orWhere('StreetName','like','%'.$term.'%')->orWhere('PostalCode','=',$term)->orWhereHas('propertylocation', function ($query) use ($term) {$query->where('CommunityName', '=',$term);})
    ->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->get();
      $PropertyLocation_count=collect($PropertyLocation_full);
      $PropertyLocation_full_count=$PropertyLocation_count->count(); 
    $PropertyLocation = PropertyDetail:: where('City', '=',$termcity)->orWhere('MLSNumber','=',$term)->orWhere('StreetNumber','like','%'.$term.'%')->orWhere('StreetName','like','%'.$term.'%')->orWhere('PostalCode','=',$term)->orWhereHas('propertylocation', function ($query) use ($term) {$query->where('CommunityName', '=',$term);})
    ->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->limit($limit)->offset($offset)->get(); 
      
      return response()->json(['listing' => $PropertyLocation, 'fullcount' => $PropertyLocation_full_count]);
    }
    public function homepage_listing(Request $request)
    {
        
          
          $city=$request['city'];
          $min_price=$request['min_price'];
          $max_price=$request['max_price'];
          $square_feet=$request['square_feet'];
          $max_days_listed=$request['max_days_listed'];
          $sort_by=$request['sort_by'];
          $sortbyfield='created_at';
            $sorttype='DESC';
          if($sort_by=='listing_desc'){
            $sortbyfield='created_at';
            $sorttype='DESC';
          }
          elseif($sort_by=='listing_asc'){
            $sortbyfield='created_at';
            $sorttype='ASC';
          }
          elseif($sort_by=='pricing_asc'){
            $sortbyfield='ListPrice';
            $sorttype='ASC';
          }
          elseif($sort_by=='pricing_desc'){
            $sortbyfield='ListPrice';
            $sorttype='DESC';
          }
          elseif($sort_by=='bedrooms_asc'){
            $sortbyfield='BedroomsTotalPossibleNum';
            $sorttype='ASC';
          }
          elseif($sort_by=='bedrooms_desc'){
            $sortbyfield='BedroomsTotalPossibleNum';
            $sorttype='DESC';
          }
          elseif($sort_by=='bathrooms_asc'){
            $sortbyfield='BathsTotal';
            $sorttype='ASC';
          }
          elseif($sort_by=='bathrooms_desc'){
            $sortbyfield='BathsTotal';
            $sorttype='DESC';
          }
          elseif($sort_by=='squarefeet_asc'){
            $sortbyfield='SqFtTotal';
            $sorttype='ASC';
          }
          elseif($sort_by=='squarefeet_desc'){
            $sortbyfield='SqFtTotal';
            $sorttype='DESC';
          }
          
          $status=$request['status'];
          //dd($status);
          if(isset($request['result_per_page']) && $request['result_per_page'] != ""){
            $limit=$request['result_per_page'];
            //dd($limit);
          }else{
            $limit=25;
          }
            if(isset($offset)){
          $start_page=($offset-1)*$limit;
        }else{
          $offset=0;
        }
        $PropertyLocation = PropertyDetail::whereHas('propertyfeature', function ($newquery) use ($property_type) {$newquery->where('PropertyType', '=',$property_type);});
        if($city!=''){
            $PropertyLocation = $PropertyLocation->wherein('City',$city);
        }
        elseif($min_price!='')
        {
            $PropertyLocation = $PropertyLocation->where('ListPrice','>=',$min_price);
        }
        elseif($max_price!='')
        {
            $PropertyLocation = $PropertyLocation->where('ListPrice','>=',$max_price);
        }
        elseif($square_feet!='')
        {
            $PropertyLocation = $PropertyLocation->where('SqFtTotal','>=',$square_feet);
        }
        elseif($status!='')
        {
           $PropertyLocation = $PropertyLocation->whereIn('Status',$status); 
        }
        
        $PropertyLocation = $PropertyLocation->orderBy($sortbyfield,$sorttype);
        $PropertyLocation = $PropertyLocation->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->limit($limit)->offset($offset)->get();
        $PropertyLocation_full = $PropertyLocation->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->get();
      $PropertyLocation_count=collect($PropertyLocation_full);
      $PropertyLocation_full_count=$PropertyLocation_count->count(); 
//$PropertyLocation = PropertyDetail::whereHas('propertyfeature', function ($newquery) use ($property_type) {$newquery->where('PropertyType', '=',$property_type);})->where('City','=',$city)->where('ListPrice','>=',$min_price)->where('ListPrice','<=',$max_price)->where('SqFtTotal','>=',$square_feet)->where('NumAcres','=',$acres)->orderBy($sortbyfield,$sorttype)
    //->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->get();
    $wordCount = $PropertyLocation->count();
    //dd($wordCount);
    //dd($PropertyLocation);
    return response()->json(['listing' => $PropertyLocation, 'fullcount' => $PropertyLocation_full_count]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function advance_search(Request $request)
    {
        //dd('test');
          $property_type=$request['property_type'];
          //dd($property_type);
          if($property_type=='RES'){
            $property_type='Residential';
          }
          elseif($property_type=='RNT'){
            $property_type='Residential Rental';
          }
          elseif($property_type=='BLD'){
            $property_type='Builder';
          }
          elseif($property_type=='LND'){
            $property_type='Vacant/Subdivided Land';
          }
          elseif($property_type=='MUL'){
            $property_type='Multiple Dwelling';
          }
          elseif($property_type=='VER'){
            $property_type='High Rise';
          }
          //dd($property_type);
          $property_sub_type=$request['property_sub_type'];
          //dd($property_sub_type);
          $city=$request['city'];
          $min_price=$request['min_price'];
          $max_price=$request['max_price'];
          $square_feet=$request['square_feet'];
          $acres=$request['acres'];
          $max_days_listed=$request['max_days_listed'];
          $sort_by=$request['sort_by'];
          $sortbyfield='created_at';
            $sorttype='DESC';
          if($sort_by=='listing_desc'){
            $sortbyfield='created_at';
            $sorttype='DESC';
          }
          elseif($sort_by=='listing_asc'){
            $sortbyfield='created_at';
            $sorttype='ASC';
          }
          elseif($sort_by=='pricing_asc'){
            $sortbyfield='ListPrice';
            $sorttype='ASC';
          }
          elseif($sort_by=='pricing_desc'){
            $sortbyfield='ListPrice';
            $sorttype='DESC';
          }
          elseif($sort_by=='bedrooms_asc'){
            $sortbyfield='BedroomsTotalPossibleNum';
            $sorttype='ASC';
          }
          elseif($sort_by=='bedrooms_desc'){
            $sortbyfield='BedroomsTotalPossibleNum';
            $sorttype='DESC';
          }
          elseif($sort_by=='bathrooms_asc'){
            $sortbyfield='BathsTotal';
            $sorttype='ASC';
          }
          elseif($sort_by=='bathrooms_desc'){
            $sortbyfield='BathsTotal';
            $sorttype='DESC';
          }
          elseif($sort_by=='squarefeet_asc'){
            $sortbyfield='SqFtTotal';
            $sorttype='ASC';
          }
          elseif($sort_by=='squarefeet_desc'){
            $sortbyfield='SqFtTotal';
            $sorttype='DESC';
          }
          
          $status=$request['status'];
          //dd($status);
          $bedrooms=$request['bedrooms'];
          $bathrooms=$request['bathrooms'];
          if(isset($request['result_per_page']) && $request['result_per_page'] != ""){
            $limit=$request['result_per_page'];
            //dd($limit);
          }else{
            $limit=25;
          }
            if(isset($offset)){
          $start_page=($offset-1)*$limit;
        }else{
          $offset=0;
        }
        $PropertyLocation = PropertyDetail::whereHas('propertyfeature', function ($newquery) use ($property_type) {$newquery->where('PropertyType', '=',$property_type);});
        if($city!=''){
            $PropertyLocation = $PropertyLocation->wherein('City',$city);
        }
        elseif($min_price!='')
        {
            $PropertyLocation = $PropertyLocation->where('ListPrice','>=',$min_price);
        }
        elseif($max_price!='')
        {
            $PropertyLocation = $PropertyLocation->where('ListPrice','>=',$max_price);
        }
        elseif($square_feet!='')
        {
            $PropertyLocation = $PropertyLocation->where('SqFtTotal','>=',$square_feet);
        }
        elseif($acres!='')
        {
            $PropertyLocation = $PropertyLocation->where('NumAcres','>=',$acres);
        }
        elseif($bedrooms!='')
        {
            $PropertyLocation = $PropertyLocation->where('BedroomsTotalPossibleNum','>=',$bedrooms);
        }
        elseif($bathrooms!='')
        {
            $PropertyLocation = $PropertyLocation->where('BathsTotal','>=',$bathrooms);
        }
        elseif($status!='')
        {
           $PropertyLocation = $PropertyLocation->whereIn('Status',$status); 
        }
        elseif($property_sub_type!='')
        {
            $PropertyLocation = $PropertyLocation->whereHas('propertyfeature', function ($new2query) use ($property_sub_type) {$new2query->whereIn('PropertySubType',$property_sub_type);});
        }
        $PropertyLocation = $PropertyLocation->orderBy($sortbyfield,$sorttype);
        $PropertyLocation = $PropertyLocation->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->limit($limit)->offset($offset)->get();
//$PropertyLocation = PropertyDetail::whereHas('propertyfeature', function ($newquery) use ($property_type) {$newquery->where('PropertyType', '=',$property_type);})->where('City','=',$city)->where('ListPrice','>=',$min_price)->where('ListPrice','<=',$max_price)->where('SqFtTotal','>=',$square_feet)->where('NumAcres','=',$acres)->orderBy($sortbyfield,$sorttype)
    //->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->get();
    $wordCount = $PropertyLocation->count();
    //dd($wordCount);
    //dd($PropertyLocation);
        return response()->json($PropertyLocation);

    }
    public function property_desc(Request $request,$matrix_unique_id)
    {
        //dd('trst');
        //dd($matrix_unique_id);
        $PropertyLocation = PropertyDetail::where('Matrix_Unique_ID','=',$matrix_unique_id);
        $PropertyLocation = $PropertyLocation->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->get();
        return response()->json($PropertyLocation);
    }
    public function photo_gallery(Request $request,$matrix_unique_id)
    {
        //dd('first');
        //dd($matrix_unique_id);
        $PropertyLocation = PropertyDetail::where('Matrix_Unique_ID','=',$matrix_unique_id);
        $PropertyLocation = $PropertyLocation->with('propertyimage')->get();
            //dd($PropertyLocation);
        return response()->json($property_full_count);
        return response()->json($PropertyLocation);
    }
    public function addresssearch(Request $request){
      //dd($request->all());
      if(isset($request['result_per_page']) && $request['result_per_page'] != ""){
            $limit=$request['result_per_page'];
            //dd($limit);
          }else{
            $limit=25;
          }
            if(isset($offset)){
          $start_page=($offset-1)*$limit;
        }else{
          $offset=0;
        }
      $property_type=$request['property_type'];
      if($property_type=='RES'){
            $property_type='Residential';
          }
          elseif($property_type=='RNT'){
            $property_type='Residential Rental';
          }
          elseif($property_type=='BLD'){
            $property_type='Builder';
          }
          elseif($property_type=='LND'){
            $property_type='Vacant/Subdivided Land';
          }
          elseif($property_type=='MUL'){
            $property_type='Multiple Dwelling';
          }
          elseif($property_type=='VER'){
            $property_type='High Rise';
          }
          $city=$request['city'];
          $county=$request['county'];
          $postal_code=$request['postal_code'];
          $house_number=$request['house_number'];
          $house_deriction=$request['house_deriction'];
          $house_name=$request['house_name'];
          $PropertyLocation = PropertyDetail::whereHas('propertyfeature', function ($newquery) use ($property_type) {$newquery->where('PropertyType', '=',$property_type);});
        if($city!=''){
            
            $PropertyLocation = $PropertyLocation->wherein('City',$city);
        }
        elseif($county!=''){

            $PropertyLocation->whereHas('propertyfeature', function ($new3query) use ($county) {$new3query->whereIn('CountyOrParish',$county);});
        }
        
        elseif($postal_code!='')
        {
           
           $PropertyLocation = $PropertyLocation->whereIn('PostalCode',$postal_code); 
        }
        elseif($house_number!='')
        {
           
           $PropertyLocation = $PropertyLocation->whereHas('propertyadditional', function ($new4query) use ($house_number) {$new4query->where('PublicAddress','Like','%' .$house_number. '%');}); 
        }
        elseif($house_deriction!='')
        {
           
           $PropertyLocation = $PropertyLocation->whereHas('propertyadditional', function ($new4query) use ($house_number) {$new4query->where('PublicAddress','Like','%' .$house_deriction. '%');}); 
        }
        elseif($house_name!='')
        {
           
           $PropertyLocation = $PropertyLocation->whereHas('propertyadditional', function ($new4query) use ($house_number) {$new4query->where('PublicAddress','Like','%' .$house_name. '%');}); 
        }
        
        $PropertyLocation = $PropertyLocation->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->limit($limit)->offset($offset)->get();

        return response()->json($PropertyLocation);
    }
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
