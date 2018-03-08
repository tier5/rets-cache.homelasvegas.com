<?php

namespace App\Http\Controllers;

use App\Jobs\InsertImages;
use App\Jobs\UpdateProperty;
use App\PropertyDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiControllerV2 extends Controller
{
    public function globalSearch(Request $request)
    {
        try {
            if ($request->has('per_page')) {
                $perPage = $request->per_page;
            } else {
                $perPage = 25;
            }
            if ($request->has('city')) {
                $property = PropertyDetail::where('City', $request->city)
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } elseif ($request->has('postal_code')) {
                $property = PropertyDetail::where('PostalCode', $request->postal_code)
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } elseif ($request->has('address')) {
                $property = PropertyDetail::where('PublicAddress', $request->address)
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } elseif ($request->has('search_community')) {
                $search_community = $request->search_community;
                $property = PropertyDetail::WhereHas('propertylocation', function ($query) use ($search_community) {
                    $query->where('CommunityName', $search_community);
                })
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } elseif ($request->has('listing_id')) {
                $property = PropertyDetail::where('MLSNumber', $request->listing_id)
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Input'
                ], 400);
            }
            if (count($property) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Search Result Found',
                    'results' => $property
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Result Found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function retsSearch(Request $request)
    {
        try{
            $searchResult = PropertyDetail::query();
            if($request->has('city')){
                $searchResult->where('City',$request->city);
            }
            if($request->has('square_feet')){
                $searchResult->where('SqFtTotal','>=',$request->square_feet);
            }
            if($request->has('min_price')){
                $searchResult->where('ListPrice','>=',$request->min_price);
            }
            if ($request->has('search_community')) {
                $search_community = $request->search_community;
                $searchResult->WhereHas('propertylocation', function ($query) use ($search_community) {
                    $query->where('CommunityName',$search_community);
                });
            }
            if($request->has('max_price')){
                $searchResult->where('ListPrice','<=',$request->max_price);
            }
            if($request->has('max_days_listed')){

                $now = Carbon::now();
                $date = $now->subDays($request->max_days_listed);
                $searchResult->where('OriginalEntryTimestamp','<=',$date->toDateTimeString());

            }
            if($request->has('status')){
                $status = explode(',',$request->status);
                $searchResult->whereIn('Status',$status);
            }
            if($request->has('result_per_page')){
                $perPage = $request->result_per_page;
            } else {
                $perPage = 25;
            }
            if($request->has('sort_by')){
                $sort_by = $request->sort_by;
                if ($sort_by == 'listing_desc') {
                    $sortbyfield = 'created_at';
                    $sorttype = 'DESC';
                } elseif ($sort_by == 'listing_asc') {
                    $sortbyfield = 'created_at';
                    $sorttype = 'ASC';
                } elseif ($sort_by == 'pricing_asc') {
                    $sortbyfield = 'ListPrice';
                    $sorttype = 'ASC';
                } elseif ($sort_by == 'pricing_desc') {
                    $sortbyfield = 'ListPrice';
                    $sorttype = 'DESC';
                } elseif ($sort_by == 'bedrooms_asc') {
                    $sortbyfield = 'BedroomsTotalPossibleNum';
                    $sorttype = 'ASC';
                } elseif ($sort_by == 'bedrooms_desc') {
                    $sortbyfield = 'BedroomsTotalPossibleNum';
                    $sorttype = 'DESC';
                } elseif ($sort_by == 'bathrooms_asc') {
                    $sortbyfield = 'BathsTotal';
                    $sorttype = 'ASC';
                } elseif ($sort_by == 'bathrooms_desc') {
                    $sortbyfield = 'BathsTotal';
                    $sorttype = 'DESC';
                } elseif ($sort_by == 'squarefeet_asc') {
                    $sortbyfield = 'SqFtTotal';
                    $sorttype = 'ASC';
                } else {
                    $sortbyfield = 'SqFtTotal';
                    $sorttype = 'DESC';
                }
                $searchResult->orderBy($sortbyfield,$sorttype);
            }
            $result = $searchResult
                ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                    'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                    'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                    'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                    'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                    'PropertyInsurance','PropertySellingDetails'])
                ->paginate($perPage);
            if(count($result) > 0){
                return response()->json([
                    'success' => true,
                    'message' => 'rets Search Result',
                    'results' => $result
                ],200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Record Found',
                ],404);
            }
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
    public function advanceSearch(Request $request)
    {
        try{
            ini_set('max_execution_time', 30000000);
            set_time_limit(0);
            ini_set('memory_limit', '2048M');
            $searchResult = PropertyDetail::query();
            if($request->has('city')){
                $city = explode(',',$request->city);
                $searchResult->whereIn('City',$city);
            }
            if($request->has('property_type')){
                $propertyType = $request->property_type;
                if ($propertyType == 'RES') {
                    $propertyType = 'Residential';
                } elseif ($propertyType == 'RNT') {
                    $propertyType = 'Residential Rental';
                } elseif ($propertyType == 'BLD') {
                    $propertyType = 'Builder';
                } elseif ($propertyType == 'LND') {
                    $propertyType = 'Vacant/Subdivided Land';
                } elseif ($propertyType == 'MUL') {
                    $propertyType = 'Multiple Dwelling';
                } else {
                    $propertyType = 'High Rise';
                }
                $searchResult->whereHas('propertyfeature', function($data) use($propertyType) {
                    $data->where('PropertyType', $propertyType);
                });
            }
            if($request->has('square_feet')){
                $searchResult->where('SqFtTotal','>=',$request->square_feet);
            }
            if($request->has('min_price')){
                $searchResult->where('ListPrice','>=',$request->min_price);
            }
            if($request->has('max_price')){
                $searchResult->where('ListPrice','<=',$request->max_price);
            }
            if($request->has('acres')){
                $searchResult->where('NumAcres',$request->acres);
            }
            if($request->has('max_days_listed')){

                 $now = Carbon::now();
                 $date = $now->subDays($request->max_days_listed);
                    $searchResult->where('OriginalEntryTimestamp','<=',$date->toDateTimeString());

            }
            if($request->has('status')){
                $status = explode(',',$request->status);
                $searchResult->whereIn('Status',$status);
            }
            if($request->has('bedrooms')){
                $searchResult->where('BedroomsTotalPossibleNum','>=',$request->bedrooms);
            }
            if($request->has('bathrooms')){
                $searchResult->where('BathsTotal','>=',$request->bathrooms);
            }
            if($request->has('result_per_page')){
                $perPage = $request->result_per_page;
            } else {
                $perPage = 25;
            }
            if($request->has('property_sub_type')){
                $propertySubType = explode(',',$request->property_sub_type);
                $searchResult->whereHas('propertyfeature', function ($new2query) use ($propertySubType) {
                    $new2query->whereIn('PropertySubType', $propertySubType);
                });
            }
            if($request->has('sort_by')){
                $sort_by = $request->sort_by;
                if ($sort_by == 'listing_desc') {
                    $sortbyfield = 'created_at';
                    $sorttype = 'DESC';
                } elseif ($sort_by == 'listing_asc') {
                    $sortbyfield = 'created_at';
                    $sorttype = 'ASC';
                } elseif ($sort_by == 'pricing_asc') {
                    $sortbyfield = 'ListPrice';
                    $sorttype = 'ASC';
                } elseif ($sort_by == 'pricing_desc') {
                    $sortbyfield = 'ListPrice';
                    $sorttype = 'DESC';
                } elseif ($sort_by == 'bedrooms_asc') {
                    $sortbyfield = 'BedroomsTotalPossibleNum';
                    $sorttype = 'ASC';
                } elseif ($sort_by == 'bedrooms_desc') {
                    $sortbyfield = 'BedroomsTotalPossibleNum';
                    $sorttype = 'DESC';
                } elseif ($sort_by == 'bathrooms_asc') {
                    $sortbyfield = 'BathsTotal';
                    $sorttype = 'ASC';
                } elseif ($sort_by == 'bathrooms_desc') {
                    $sortbyfield = 'BathsTotal';
                    $sorttype = 'DESC';
                } elseif ($sort_by == 'squarefeet_asc') {
                    $sortbyfield = 'SqFtTotal';
                    $sorttype = 'ASC';
                } else {
                    $sortbyfield = 'SqFtTotal';
                    $sorttype = 'DESC';
                }
                $searchResult->orderBy($sortbyfield,$sorttype);
            }
            $result = $searchResult
                ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                    'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                    'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                    'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                    'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                    'PropertyInsurance','PropertySellingDetails'])
                ->paginate($perPage);
            if(count($result) > 0){
                return response()->json([
                    'success' => true,
                    'message' => 'Advance Search Result',
                    'results' => $result
                ],200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Record Found',
                ],404);
            }
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
    public function addressSearch(Request $request)
    {
        try{
            $perPage = 25;
            $search = PropertyDetail::query();
            if($request->has('property_type')){
                $propertyType = $request->property_type;
                if ($propertyType == 'RES') {
                    $propertyType = 'Residential';
                } elseif ($propertyType == 'RNT') {
                    $propertyType = 'Residential Rental';
                } elseif ($propertyType == 'BLD') {
                    $propertyType = 'Builder';
                } elseif ($propertyType == 'LND') {
                    $propertyType = 'Vacant/Subdivided Land';
                } elseif ($propertyType == 'MUL') {
                    $propertyType = 'Multiple Dwelling';
                } elseif ($propertyType == 'VER') {
                    $propertyType = 'High Rise';
                }
                $search->whereHas('propertyfeature', function($data) use($propertyType) {
                    $data->where('PropertyType', $propertyType);
                });
            }
            if($request->has('city')){
                $city = explode(',',$request->city);
                $search->whereIn('City',$city);
            }
            if($request->has('county')){
                $county = explode(',',$request->county);
                $search->whereHas('propertyfeature', function ($new3query) use ($county) {
                    $new3query->whereIn('CountyOrParish', $county);
                });
            }
            if($request->has('postal_code')){
                $postalCode = explode(',',$request->postal_code);
                $search->whereIn('PostalCode',$postalCode);
            }
            if($request->has('house_number')){
                $house_number = $request->house_number;
                $search->where('PublicAddress','LIKE','%'.$house_number.'%');
                /*$search->whereHas('propertyadditional', function ($new4query) use ($house_number) {
                    $new4query->whereIn('PublicAddress', 'Like', '%' . $house_number . '%');
                });*/
            }
            if($request->has('house_deriction')){
                $house_derictions = $request->house_deriction;
                $search->where('PublicAddress','LIKE','%'.$house_derictions.'%');
                /*$search->whereHas('propertyadditional', function ($new4query) use ($house_derictions) {
                    $new4query->whereIn('PublicAddress', 'Like', '%' . $house_derictions . '%');
                });*/
            }
            if($request->has('house_name')){
                $house_name = $request->house_name;
                $search->where('PublicAddress','LIKE','%'.$house_name.'%');
                /*$search->whereHas('propertyadditional', function ($new4query) use ($house_name) {
                    $new4query->whereIn('PublicAddress', 'Like', '%' . $house_name . '%');
                });*/
            }
            if($request->has_image == 'Image'){
                $search->where('PhotoCount','>',0);
            }
            if($request->virtual_tour == 'VT'){
                $search->where('VirtualTourLink','!=','');
            }
            if($request->open_house == 'OH'){
                //Later
            }
            $searchResult = $search
                ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                    'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                    'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                    'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                    'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                    'PropertyInsurance','PropertySellingDetails'])
                ->paginate($perPage);
            if(count($searchResult) > 0){
                return response()->json([
                    'success' => true,
                    'message' => 'Address Search Result',
                    'results' => $searchResult
                ],200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Record Found',
                ],404);
            }
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
    public function advanceListing(Request $request)
    {
        try{
            if($request->has('result_per_page')){
                $perPage = $request->result_per_page;
            } else {
                $perPage = 25;
            }
            if($request->has('listing_id')){
                $listingId =explode(',',$request->listing_id);
                $property = PropertyDetail::whereIn('MLSNumber',$listingId)
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
                if(count($property) > 0){
                    return response()->json([
                        'success' => true,
                        'message' => 'Property Details found',
                        'results' => $property
                    ],200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Property Not Found'
                    ],404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Please Insert MLS numbers'
                ],400);
            }
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
    public function viewMore($matrix_unique_id)
    {
        try {
            $PropertyLocation = PropertyDetail::where('Matrix_Unique_ID', '=', $matrix_unique_id)
                ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                    'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                    'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                    'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                    'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                    'PropertyInsurance','PropertySellingDetails'])
                ->first();
            if (count($PropertyLocation->propertyimage) < 3) {
                $this->updateGalary($matrix_unique_id);
                return response()->json([
                    'success' => true,
                    'message' => 'Live Images',
                    'results' => $PropertyLocation,
                    'hasImage' => false
                ],200);
            } else {
                $this->thresholdCheck($matrix_unique_id);
                return response()->json([
                    'success' => true,
                    'message' => 'Database Images',
                    'results' => $PropertyLocation,
                    'hasImage' => true
                ],200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],500);
        }
    }
    public function photoGallery($matrix_unique_id)
    {
        try{
            $property = PropertyDetail::where('Matrix_Unique_ID',$matrix_unique_id)
                ->with('propertyimage')->first();
            if (count($property->propertyimage) < 3) {
                $this->updateGalary($matrix_unique_id);
                return response()->json([
                    'success' => true,
                    'message' => 'Live Images',
                    'results' => $property,
                    'hasImage' => false
                ],200);
            } else {
                $this->thresholdCheck($matrix_unique_id);
                return response()->json([
                    'success' => true,
                    'message' => 'Database Images',
                    'results' => $property,
                    'hasImage' => true
                ],200);
            }
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
    public function mortgageCalculator($matrix_unique_id)
    {
        try{
            $propertyMortgage = PropertyDetail::where('Matrix_Unique_ID', '=', $matrix_unique_id)
                ->with('propertyimage')
                ->first();
            $this->thresholdCheck($matrix_unique_id);
            if($propertyMortgage != null){
                return response()->json([
                    'success' => true,
                    'message' => 'Mortgage Calculator',
                    'results' => $propertyMortgage
                ],200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Property Not Found'
                ],404);
            }
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
    public function printableFlyer($matrix_unique_id)
    {
        try{
            $propertyPrintable = PropertyDetail::where('Matrix_Unique_ID', '=', $matrix_unique_id)
                ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                    'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                    'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                    'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                    'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                    'PropertyInsurance','PropertySellingDetails'])
                ->first();
            $this->thresholdCheck($matrix_unique_id);
            if($propertyPrintable != null){
                return response()->json([
                    'success' => true,
                    'message' => 'Result for property printable',
                    'results' => $propertyPrintable
                ],200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Property Not Found'
                ],404);
            }
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
    public function thresholdCheck($Matrix_Unique_ID)
    {
        $propertyDetails = PropertyDetail::where('Matrix_Unique_ID', $Matrix_Unique_ID)->first();
        if ($propertyDetails != null) {
            $now = Carbon::now();
            $date = Carbon::parse($propertyDetails->updated_at);
            $diff = $date->diffInDays($now);
            if ($diff >= env('THRESHOLD')) {
                $job = (new UpdateProperty($Matrix_Unique_ID));
                $this->dispatch($job);
            } else {
            }
        } else {
            try {
                $job = (new UpdateProperty($Matrix_Unique_ID));
                $this->dispatch($job);
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }
    }
    public function updateGalary($Matrix_Unique_ID)
    {
        $propertyDetails = PropertyDetail::where('Matrix_Unique_ID', $Matrix_Unique_ID)->first();
        if ($propertyDetails != null) {
            $job = (new InsertImages($propertyDetails->Matrix_Unique_ID,$propertyDetails->MLSNumber));
            $this->dispatch($job);
        } else {
            try {
                $job = (new UpdateProperty($Matrix_Unique_ID));
                $this->dispatch($job);
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }
    }
    
    public function globalListingSearch(Request $request) {
        try {
            if ($request->has('per_page')) {
                $perPage = $request->per_page;
            } else {
                $perPage = 25;
            }
            if ($request->has('city')) {
                $property = PropertyDetail::where('City', $request->city)->where('Status', 'Active')
                ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } elseif ($request->has('postal_code')) {
                $property = PropertyDetail::where('PostalCode', $request->postal_code)->where('Status', 'Active')
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } elseif ($request->has('address')) {
                $property = PropertyDetail::where('PublicAddress', $request->address)->where('Status', 'Active')
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } elseif ($request->has('search_community')) {
                $search_community = $request->search_community;
                $property = PropertyDetail::WhereHas('propertylocation', function ($query) use ($search_community) {
                    $query->where('CommunityName', $search_community);
                })->where('Status', 'Active')
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } elseif ($request->has('listing_id')) {
                $property = PropertyDetail::where('MLSNumber', $request->listing_id)->where('Status', 'Active')
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                        'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                        'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                        'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                        'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                        'PropertyInsurance','PropertySellingDetails'])
                    ->paginate($perPage);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Input'
                ], 400);
            }
            if (count($property) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Search Result Found',
                    'results' => $property
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Result Found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
