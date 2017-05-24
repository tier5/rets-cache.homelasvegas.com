<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateProperty;
use Carbon\Carbon;
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
use App\Jobs\InsertImages;
use phRETS;

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
        $term = $request['search_input'];
        //dd($term);
        $myArray = "";
        $postal_code = "";
        $community = "";
        $offset = $request['offset'];
        $termcity = $request['city'];
        //dd($termcity);
        $termlisting_id = $request['listing_id'];
        //dd($termcity.''.$termlisting_id);
        $termlisting_address = $request['address'];
        $termlisting_postal_code = $request['postal_code'];
        if ($termlisting_postal_code != "") {
            $postal_code = $termlisting_postal_code;
        }
        $termlisting_search_community = $request['search-community'];
        if ($termlisting_search_community != "") {
            $community = $termlisting_search_community;
        }
        //dd($termlisting_address);
        if ($termlisting_address != "") {
            $myArray = explode(',', $termlisting_address);
            //dd($myArray);
            $termstreet_no = $myArray[0];
            $termstreet_name = $myArray[1];
            //dd($termstreet_name);
        } else {
            $termstreet_no = "";
            $termstreet_name = "";
        }
        if (isset($request['result_per_page']) && $request['result_per_page'] != "") {
            $limit = $request['result_per_page'];
            //dd($limit);
        } else {
            $limit = 25;
        }
        if (isset($offset)) {
            $start_page = ($offset - 1) * $limit;
        } else {
            $offset = 0;
        }
        //dd($term);
        //dd($myArray[0]);
        //dd($termcity.'$'.$termlisting_address);

        //$PropertyLocation = PropertyDetail:: where('City', '=', $termcity)->orWhere('MLSNumber','=',$termlisting_id)->orWhere('StreetNumber','=',$termstreet_no)->orWhere('StreetName','=',$termstreet_name)->orWhereHas('propertylocation', function ($query) use ($community) {$query->where('CommunityName', '=',$community);})
        //->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->toSql();
        //dd($PropertyLocation);
        $PropertyLocation_full = PropertyDetail:: where('City', '=', $termcity)->orWhere('MLSNumber', '=', $term)->orWhere('StreetNumber', 'like', '%' . $term . '%')->orWhere('StreetName', 'like', '%' . $term . '%')->orWhere('PostalCode', '=', $term)->orWhereHas('propertylocation', function ($query) use ($term) {
            $query->where('CommunityName', '=', $term);
        })
            ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->get();
        $PropertyLocation_count = collect($PropertyLocation_full);
        $PropertyLocation_full_count = $PropertyLocation_count->count();
        $PropertyLocation = PropertyDetail:: where('City', '=', $termcity)->orWhere('MLSNumber', '=', $term)->orWhere('StreetNumber', 'like', '%' . $term . '%')->orWhere('StreetName', 'like', '%' . $term . '%')->orWhere('PostalCode', '=', $term)->orWhereHas('propertylocation', function ($query) use ($term) {
            $query->where('CommunityName', '=', $term);
        })
            ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->limit($limit)->offset($offset)->get();

        return response()->json(['listing' => $PropertyLocation, 'fullcount' => $PropertyLocation_full_count, 'home_page' => 1]);
    }

    public function homepage_listing(Request $request)
    {
        $city = $request['city'];
        $min_price = $request['min_price'];
        $max_price = $request['max_price'];
        $square_feet = $request['square_feet'];
        $max_days_listed = $request['max_days_listed'];
        $sort_by = $request['sort_by'];
        $sortbyfield = 'created_at';
        $sorttype = 'DESC';
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
        } elseif ($sort_by == 'squarefeet_desc') {
            $sortbyfield = 'SqFtTotal';
            $sorttype = 'DESC';
        }

        $status = $request['status'];
        //return response()->json(['status' => $request['status']]);
        //dd($status);
        if (isset($request['result_per_page']) && $request['result_per_page'] != "") {
            $limit = $request['result_per_page'];
            //dd($limit);
        } else {
            $limit = 25;
        }
        if (isset($offset)) {
            $start_page = ($offset - 1) * $limit;
        } else {
            $offset = 0;
        }
        //dd($city);
        $PropertyLocation = PropertyDetail::where('City', '=', $city);
        if ($min_price != '') {
            $PropertyLocation = $PropertyLocation->where('ListPrice', '>=', $min_price);
        }
        if ($max_price != '') {
            $PropertyLocation = $PropertyLocation->where('ListPrice', '<=', $max_price);
        }
        if ($square_feet != '') {
            $PropertyLocation = $PropertyLocation->where('SqFtTotal', '>=', $square_feet);
        }
        if ($status != '') {
            //dd($status);
            $status = explode(',', $status);
            $PropertyLocation = $PropertyLocation->whereIn('Status', $status);
        }

        $PropertyLocation = $PropertyLocation->orderBy($sortbyfield, $sorttype);
        $PropertyLocation_list = $PropertyLocation->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->limit($limit)->offset($offset)->get();
        //$PropertyLocation_count=collect($PropertyLocation_list);
        //$PropertyLocation_full_count=$PropertyLocation_list->count();
        //dd($PropertyLocation_full_count);
        $PropertyLocation_full = $PropertyLocation->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->get();
        $PropertyLocation_count = collect($PropertyLocation_full);
        $PropertyLocation_full_count = $PropertyLocation_count->count();
        //dd($PropertyLocation_full_count);
        //$PropertyLocation = PropertyDetail::whereHas('propertyfeature', function ($newquery) use ($property_type) {$newquery->where('PropertyType', '=',$property_type);})->where('City','=',$city)->where('ListPrice','>=',$min_price)->where('ListPrice','<=',$max_price)->where('SqFtTotal','>=',$square_feet)->where('NumAcres','=',$acres)->orderBy($sortbyfield,$sorttype)
        //->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->get();
        //$wordCount = $PropertyLocation->count();
        //dd($wordCount);
        //dd($PropertyLocation);
        //return response()->json($PropertyLocation_list);
        return response()->json(['listing' => $PropertyLocation_list, 'fullcount' => $PropertyLocation_full_count, 'home_listing' => 'yes']);
    }

    public function advance_listing(Request $request)
    {

        $listing_id = $request['listing_id'];

        //dd($status);
        if (isset($request['result_per_page']) && $request['result_per_page'] != "") {
            $limit = $request['result_per_page'];
            //dd($limit);
        } else {
            $limit = 25;
        }
        if (isset($offset)) {
            $start_page = ($offset - 1) * $limit;
        } else {
            $offset = 0;
        }
        $PropertyLocation = PropertyDetail::where('MLSNumber', '=', $listing_id);

        $PropertyLocation_list = $PropertyLocation->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->limit($limit)->offset($offset)->get();
        $PropertyLocation_full = $PropertyLocation->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->get();
        $PropertyLocation_count = collect($PropertyLocation_full);
        $PropertyLocation_full_count = $PropertyLocation_count->count();
        $wordCount = $PropertyLocation->count();
        //dd($wordCount);
        //dd($PropertyLocation);
        return response()->json(['listing' => $PropertyLocation_list, 'fullcount' => $PropertyLocation_full_count]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function advance_search(Request $request)
    {
        //dd('test');
        $property_type = $request['property_type'];
        //dd($property_type);
        if ($property_type == 'RES') {
            $property_type = 'Residential';
        } elseif ($property_type == 'RNT') {
            $property_type = 'Residential Rental';
        } elseif ($property_type == 'BLD') {
            $property_type = 'Builder';
        } elseif ($property_type == 'LND') {
            $property_type = 'Vacant/Subdivided Land';
        } elseif ($property_type == 'MUL') {
            $property_type = 'Multiple Dwelling';
        } elseif ($property_type == 'VER') {
            $property_type = 'High Rise';
        }
        //dd($property_type);
        $property_sub_type = $request['property_sub_type'];
        //dd($property_sub_type);
        $city = $request['city'];
        $min_price = $request['min_price'];
        $max_price = $request['max_price'];
        $square_feet = $request['square_feet'];
        $acres = $request['acres'];
        $max_days_listed = $request['max_days_listed'];
        $sort_by = $request['sort_by'];
        $sortbyfield = 'created_at';
        $sorttype = 'DESC';
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
        } elseif ($sort_by == 'squarefeet_desc') {
            $sortbyfield = 'SqFtTotal';
            $sorttype = 'DESC';
        }

        $status = $request['status'];
        //dd($status);
        $bedrooms = $request['bedrooms'];
        $bathrooms = $request['bathrooms'];
        if (isset($request['result_per_page']) && $request['result_per_page'] != "") {
            $limit = $request['result_per_page'];
            //dd($limit);
        } else {
            $limit = 25;
        }
        if (isset($offset)) {
            $start_page = ($offset - 1) * $limit;
        } else {
            $offset = 0;
        }
        $PropertyLocation = PropertyDetail::whereHas('propertyfeature', function ($newquery) use ($property_type) {
            $newquery->where('PropertyType', '=', $property_type);
        });
        if ($city != '') {
            $city = explode(',', $city);
            $PropertyLocation = $PropertyLocation->wherein('City', $city);
        }
        if ($min_price != '') {
            $PropertyLocation = $PropertyLocation->where('ListPrice', '>=', $min_price);
        }
        if ($max_price != '') {
            $PropertyLocation = $PropertyLocation->where('ListPrice', '>=', $max_price);
        }
        if ($square_feet != '') {
            $PropertyLocation = $PropertyLocation->where('SqFtTotal', '>=', $square_feet);
        }
        if ($acres != '') {
            $PropertyLocation = $PropertyLocation->where('NumAcres', '>=', $acres);
        }
        if ($bedrooms != '') {
            $PropertyLocation = $PropertyLocation->where('BedroomsTotalPossibleNum', '>=', $bedrooms);
        }
        if ($bathrooms != '') {
            $PropertyLocation = $PropertyLocation->where('BathsTotal', '>=', $bathrooms);
        }
        if ($status != '') {
            //dd($status);
            $status = explode(',', $status);
            $PropertyLocation = $PropertyLocation->whereIn('Status', $status);
        }
        if ($property_sub_type != '') {
            $PropertyLocation = $PropertyLocation->whereHas('propertyfeature', function ($new2query) use ($property_sub_type) {
                $new2query->whereIn('PropertySubType', $property_sub_type);
            });
        }
        $PropertyLocation = $PropertyLocation->orderBy($sortbyfield, $sorttype);
        $PropertyLocation_list = $PropertyLocation->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->limit($limit)->offset($offset)->get();
        $PropertyLocation_full = $PropertyLocation->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->get();
        $PropertyLocation_count = collect($PropertyLocation_full);
        $PropertyLocation_full_count = $PropertyLocation_count->count();
        //$wordCount = $PropertyLocation->count();
        //dd($wordCount);
        //dd($PropertyLocation_full_count);
        return response()->json(['listing' => $PropertyLocation_list, 'fullcount' => $PropertyLocation_full_count]);

    }

    public function property_desc(Request $request, $matrix_unique_id)
    {
        try{
            $PropertyLocation = PropertyDetail::where('Matrix_Unique_ID', '=', $matrix_unique_id)
                ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])
                ->first();
            if (count($PropertyLocation->propertyimage) < 3) {
                $rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
                $rets_username = "neal";
                $rets_password = "glvar";
                $rets = new phRETS;
                $rets->AddHeader("RETS-Version", "RETS/1.7.2");
                $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
                if ($connect) {
                    $query = "(Matrix_Unique_ID={$matrix_unique_id})";
                    $search = $rets->Search("Property", "Listing", $query, array("StandardNames" => 0));
                    $photos = $rets->GetObject("Property", "LargePhoto", $matrix_unique_id, "*", 0);
                    $imagejob = (new InsertImages($matrix_unique_id, $PropertyLocation->MLSNumber));
                    $this->dispatch($imagejob);
                    return response()->json([
                        'PropertyLocation'=> $PropertyLocation,
                        'images' => $photos
                    ]);
                }
            }
            return response()->json($PropertyLocation);
        } catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function photo_gallery($matrix_unique_id, $mls_number)
    {

        $PropertyLocation = PropertyDetail::where('Matrix_Unique_ID', '=', $matrix_unique_id);
        $PropertyLocation = $PropertyLocation->with('propertyimage')->get();

        $updated_at = $PropertyLocation[0]->updated_at;
        $now = Carbon::now();


        if (count($PropertyLocation) > 2) {

            $diff = $updated_at->diff($now)->days;

            if ($diff >= env('THRESHOLD')) {

                $imagejob = (new InsertImages($matrix_unique_id, $mls_number));
                $this->dispatch($imagejob);

            }

            return response()->json($PropertyLocation);

        } else {

            //call retsapi directally


            $rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
            $rets_username = "neal";
            $rets_password = "glvar";


            $rets = new phRETS;

            $rets->AddHeader("RETS-Version", "RETS/1.7.2");

            $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);


            if ($connect) {


                $query = "(Matrix_Unique_ID={$matrix_unique_id})";
                $search = $rets->Search("Property", "Listing", $query, array("StandardNames" => 0));

                $photos = $rets->GetObject("Property", "LargePhoto", $matrix_unique_id, "*", 0);


                $imagejob = (new InsertImages($matrix_unique_id, $mls_number));
                $this->dispatch($imagejob);


                return response()->json($search);
                //backend process to add images into database

            }
        }
    }

    public function addresssearch(Request $request)
    {
        //dd($request->all());
        if (isset($request['result_per_page']) && $request['result_per_page'] != "") {
            $limit = $request['result_per_page'];
            //dd($limit);
        } else {
            $limit = 25;
        }
        if (isset($offset)) {
            $start_page = ($offset - 1) * $limit;
        } else {
            $offset = 0;
        }
        $property_type = $request['property_type'];
        if ($property_type == 'RES') {
            $property_type = 'Residential';
        } elseif ($property_type == 'RNT') {
            $property_type = 'Residential Rental';
        } elseif ($property_type == 'BLD') {
            $property_type = 'Builder';
        } elseif ($property_type == 'LND') {
            $property_type = 'Vacant/Subdivided Land';
        } elseif ($property_type == 'MUL') {
            $property_type = 'Multiple Dwelling';
        } elseif ($property_type == 'VER') {
            $property_type = 'High Rise';
        }
        $city = $request['city'];
        //return response()->json($city);
        $county = $request['county'];
        $postal_code = $request['postal_code'];
        $house_number = $request['house_number'];
        $house_deriction = $request['house_deriction'];
        $house_name = $request['house_name'];
        $PropertyLocation = PropertyDetail::whereHas('propertyfeature', function ($newquery) use ($property_type) {
            $newquery->where('PropertyType', '=', $property_type);
        });
        if ($city != '') {
            $city = explode(',', $city);

            $PropertyLocation = $PropertyLocation->wherein('City', $city);
        } elseif ($county != '') {
            $county = explode(',', $county);
            $PropertyLocation->whereHas('propertyfeature', function ($new3query) use ($county) {
                $new3query->whereIn('CountyOrParish', $county);
            });
        } elseif ($postal_code != '') {
            $postal_code = explode(',', $postal_code);
            $PropertyLocation = $PropertyLocation->whereIn('PostalCode', $postal_code);
        } elseif ($house_number != '') {
            $house_number = explode(',', $house_number);
            $PropertyLocation = $PropertyLocation->whereHas('propertyadditional', function ($new4query) use ($house_number) {
                $new4query->where('PublicAddress', 'Like', '%' . $house_number . '%');
            });
        } elseif ($house_deriction != '') {

            $PropertyLocation = $PropertyLocation->whereHas('propertyadditional', function ($new4query) use ($house_number) {
                $new4query->where('PublicAddress', 'Like', '%' . $house_deriction . '%');
            });
        } elseif ($house_name != '') {

            $PropertyLocation = $PropertyLocation->whereHas('propertyadditional', function ($new4query) use ($house_number) {
                $new4query->where('PublicAddress', 'Like', '%' . $house_name . '%');
            });
        }

        $PropertyLocation_list = $PropertyLocation->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->limit($limit)->offset($offset)->get();

        $PropertyLocation_full = $PropertyLocation->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->get();
        $PropertyLocation_count = collect($PropertyLocation_full);
        $PropertyLocation_full_count = $PropertyLocation_count->count();
        //$wordCount = $PropertyLocation->count();
        //dd($wordCount);
        //dd($PropertyLocation_full_count);
        return response()->json(['listing' => $PropertyLocation_list, 'fullcount' => $PropertyLocation_full_count]);
    }

    public function mortgage_calculator(Request $request, $matrix_unique_id)
    {
        $PropertyMortgage = PropertyDetail::where('Matrix_Unique_ID', '=', $matrix_unique_id);
        $PropertyMortgage = $PropertyMortgage->with('propertyimage')->get();
        //dd($PropertyLocation);
        return response()->json($PropertyMortgage);
    }


    public function printable_flyer(Request $request, $matrix_unique_id)
    {
        $PropertyPrintable = PropertyDetail::where('Matrix_Unique_ID', '=', $matrix_unique_id);
        $PropertyPrintable = $PropertyPrintable->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])->get();
        //dd($PropertyLocation);
        return response()->json($PropertyPrintable);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
                return 'yes';
            } else {
                return 'yes';
            }
        } else {
            try {
                $job = (new UpdateProperty($Matrix_Unique_ID));
                $this->dispatch($job);
                return 'yes';
            } catch (\Exception $e) {
                return 'not';
            }
        }
    }
}
