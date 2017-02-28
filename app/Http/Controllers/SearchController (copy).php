<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phRETS;
use \DB;
use App\PropertyDetail;


class SearchController extends Controller
{
    public function index()
    {
    	$data = array();

    	return view('rets.search', $data);
    }

    public function do_search(Request $request)
    {


    	$city = $request->input('city');
	    // $community = $request->input('community');
	    // $min_price = $request->input('min_price');
	    // $max_price = $request->input('max_price');
	    // $square_feet = $request->input('square_feet');
	    // $max_days_listed = $request->input('max_days_listed');
	    // $sort_by = $request->input('sort_by');
	    // $status = $request->input('status');
	    // $offset = $request->input('offset');
	    // $limit = $request->input('limit');


		// if(isset($offset)){
		//   $start_page=($offset-1)*$limit;
		// }else{
		//   $offset=$limit;
		// }

    	$rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
		$rets_username = "neal";
		$rets_password = "glvar";

		$rets = new phRETS;

		$rets->AddHeader("RETS-Version", "RETS/1.7.2");

		$connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);

		if ($connect) {
		        echo "  + Connected";
		       
		        if($city!=""){
				$query="(City={$city})";
    			}

// $classes = $rets->GetMetadataClasses('Property');
// print_r($classes);
//$results = $rets->Search('Property', 'Listing', '', ['Select' => 'AccessibilityFeatures','Area']);



// 				$results = $rets->Search(
//              			"Property",
// 			            "Listing",
//              			$query,
// 			    [
// 			        'QueryType' => 'DMQL2',
// 			        //'Count' => 1, // count and records
// 			        //'Format' => 'COMPACT-DECODED',
// 			        //'Limit' => 99999999,
// 			        'StandardNames' => 0, // give system names
// 			    ]
// 			);

// echo count($results);
// dd($results);exit;

		        $results = $rets->SearchQuery(
			    "Property",
			    "Listing",
			    $query);
			   
		        $result_count=$rets->TotalRecordsFound();
		        echo $result_count;



for($i=0 ; $i< $result_count; $i++ )
{
	$listing = $rets->FetchRow($results);
	// echo $listing['Matrix_Unique_ID']."<br />\n";
	// echo $listing['ListPrice']."<br />\n";
	// echo $listing['Status']."<br />\n";
	// echo $listing['BedroomsTotalPossibleNum']."<br />\n";
 //    echo $listing['BathsTotal']."<br />\n";
	// echo $listing['BathsHalf']."<br />\n";
	// echo $listing['BathsFull']."<br />\n";
	// echo $listing['NumAcres']."<br />\n";
	// echo $listing['SqFtTotal']."<br />\n";
 //    echo $listing['MLSNumber']."<br />\n";
	// echo $listing['StreetNumber']."<br />\n";
	// echo $listing['StreetName']."<br />\n";
	// echo $listing['City']."----------------<br />\n";
// $values[]= array('Matrix_Unique_ID' => $listing['Matrix_Unique_ID'], 
// 	           'ListPrice' 		  => $listing['ListPrice'], 
// 	           'Status' 		  => $listing['Status'], 
// 	           'BedroomsTotalPossibleNum' => $listing['BedroomsTotalPossibleNum'], 
// 	           'BathsTotal'=> $listing['BathsTotal'], 
// 	           'BathsHalf' => $listing['BathsHalf'], 
// 	           'BathsFull' => $listing['BathsFull'], 
// 	           'NumAcres'  => $listing['NumAcres'], 
// 	           'SqFtTotal' => $listing['SqFtTotal'], 
// 	           'StreetNumber' => $listing['StreetNumber'], 
// 	           'StreetName'   => $listing['StreetName'], 
// 	           'City'         => $listing['City'], 
// 	           'MLSNumber'    => $listing['MLSNumber']
// 	          );
// //dd($values);
              if($listing['BathsHalf']== '') 
              {
              	$listing['BathsHalf'] =0;
              }

              if($listing['BathsFull']== '') 
              {
              	$listing['BathsFull'] =0;
              }

              if($listing['BedroomsTotalPossibleNum']== '') 
              {
              	$listing['BedroomsTotalPossibleNum'] =0;
              }

              if($listing['SqFtTotal']== '') 
              {
              	$listing['SqFtTotal'] =0;
              }

               $property =new PropertyDetail();
    	 	   $property->Matrix_Unique_ID 		= $listing['Matrix_Unique_ID'];
	           $property->ListPrice 		    = $listing['ListPrice'];
	           $property->Status 		        = $listing['Status'];
	           $property->BedroomsTotalPossibleNum = $listing['BedroomsTotalPossibleNum']; 
	           $property->BathsTotal= $listing['BathsTotal']; 
	           $property->BathsHalf = $listing['BathsHalf']; 
	           $property->BathsFull = $listing['BathsFull']; 
	           $property->NumAcres  = $listing['NumAcres']; 
	           $property->SqFtTotal = $listing['SqFtTotal']; 
	           $property->StreetNumber = $listing['StreetNumber']; 
	           $property->StreetName   = $listing['StreetName']; 
	           $property->City         = $listing['City'];
	           $property->MLSNumber    = $listing['MLSNumber'];
    	       $property->save();


	// echo $listing['Area']."<br />\n";
	// echo $listing['StateOrProvince']."<br />\n";
	// echo $listing['PublicAddress']."<br />\n";
  //    echo $listing['PostalCode']."----<br />\n";

	// echo $listing['VirtualTourLink']."<br />\n";
	// echo $listing['NumGAcres']."<br />\n";
	// echo $listing['PhotoCount']."<br />\n";
	// echo $listing['MLSNumber']."<br />\n";
	// echo $listing['OriginalEntryTimestamp']."<br />\n";
	// echo $listing['CommunityName']."<br />\n";


	// echo $listing['YearBuilt']."<br />\n";
 //    echo $listing['PropertyType']."<br />\n";
 //    echo $listing['PropertySubType']."<br />\n";
 //    echo $listing['CountyOrParish']."<br />\n";
 //    echo $listing['Zoning']."<br />\n";
 //    echo $listing['BuildingDescription']."<br />\n";
 //    echo $listing['BuiltDescription']."<br />\n";
 //    echo $listing['ConstructionDescription']."<br />\n";
 //    echo $listing['ConvertedGarageYN']."<br />\n";
 //    echo $listing['EquestrianDescription']."<br />\n";
 //    echo $listing['Fence']."<br />\n";
 //    echo $listing['FenceType']."<br />\n";
 //    echo $listing['Garage']."<br />\n";
 //    echo $listing['GarageDescription']."<br />\n";
 //    echo $listing['HouseViews']."<br />\n";
 //    echo $listing['LandscapeDescription']."<br />\n";
 //    echo $listing['LotDescription']."<br />\n";
 //    echo $listing['LotSqft']."<br />\n";
 //    echo $listing['ParkingDescription']."<br />\n";
 //    echo $listing['PoolDescription']."<br />\n";
 //    //$listing['LotSqft'];
 //    echo $listing['RoofDescription']."<br />\n";
 //    echo $listing['Sewer']."<br />\n";
 //    echo $listing['SolarElectric']."<br />\n";
 //    echo $listing['Type']."<br />\n";
 //    echo $listing['BuiltDescription']."<br />\n";
 //    echo $listing['AgeRestrictedCommunityYN']."<br />\n";
 //    echo $listing['Assessments']."<br />\n";
 //    echo $listing['AssociationFeaturesAvailable']."<br />\n";
 //    echo $listing['AssociationFeeIncludes']."<br />\n";
 //    echo $listing['AssociationName']."<br />\n";
 //    echo $listing['Builder']."<br />\n";
 //    echo $listing['CensusTract']."<br />\n";
 //    echo $listing['CourtApproval']."<br />\n";
 //    //$listing['CourtApproval'];
 //    echo $listing['GatedYN']."<br />\n";
 //    echo $listing['GreenBuildingCertificationYN']."<br />\n";
 //    echo $listing['ListingAgreementType']."<br />\n";
 //    echo $listing['Litigation']."<br />\n";
 //    echo $listing['MasterPlanFeeMQYN']."<br />\n";
 //    echo $listing['Model']."<br />\n";
 //    echo $listing['OwnerLicensee']."<br />\n";
 //    echo $listing['Ownership']."<br />\n";
 //    echo $listing['PoweronorOff']."<br />\n";
 //    echo $listing['PropertyDescription']."<br />\n";
 //    echo $listing['RealtorYN']."<br />\n";
 //    echo $listing['RefrigeratorYN']."<br />\n";
 //    echo $listing['Spa']."<br />\n";
 //    echo $listing['SpaDescription']."<br />\n";
 //    echo $listing['YearRoundSchoolYN']."<br />\n";
 //    echo $listing['AnnualPropertyTaxes']."<br />\n";
 //    echo $listing['AssociationFeeYN']."<br />\n";
 //    echo $listing['AssociationFee1']."<br />\n";
 //    echo $listing['AssociationFee1MQYN']."<br />\n";
 //    echo $listing['AVMYN']."<br />\n";
 //    echo $listing['CurrentPrice']."<br />\n";
 //    echo $listing['EarnestDeposit']."<br />\n";
 //    echo $listing['FinancingConsidered']."<br />\n";
 //    echo $listing['ForeclosureCommencedYN']."<br />\n";
 //    echo $listing['MasterPlanFeeAmount']."<br />\n";
 //    echo $listing['RATIO_CurrentPrice_By_SQFT']."<br />\n";
 //    echo $listing['RepoReoYN']."<br />\n";
 //    echo $listing['ShortSale']."<br />\n";
 //    echo $listing['SIDLIDYN']."<br />\n";
 //    echo $listing['ApproxTotalLivArea']."<br />\n";
 //    echo $listing['BathDownYN']."<br />\n";
 //    echo $listing['BathDownstairsDescription'];
 //    echo $listing['BedroomDownstairsYN'];
 //    //$listing['FinancingConsidered'];
 //    echo $listing['CoolingDescription']."<br />\n";
 //    echo $listing['CoolingFuel']."<br />\n";
 //    echo $listing['DishwasherYN']."<br />\n";
 //    echo $listing['DisposalYN']."<br />\n";
 //    echo $listing['DryerIncluded']."<br />\n";
 //    echo $listing['DryerUtilities']."<br />\n";
 //    echo $listing['EnergyDescription']."<br />\n";
 //    echo $listing['FireplaceDescription']."<br />\n";
 //    echo $listing['FireplaceLocation']."<br />\n";
 //    echo $listing['Fireplaces']."<br />\n";
 //    echo $listing['FlooringDescription']."<br />\n";
 //    echo $listing['FurnishingsDescription']."<br />\n";
 //    echo $listing['HeatingDescription']."<br />\n";
 //    echo $listing['HeatingFuel']."<br />\n";
 //    echo $listing['Interior']."<br />\n";
 //    echo $listing['NumDenOther']."<br />\n";
 //    echo $listing['OtherApplianceDescription']."<br />\n";
 //    echo $listing['OvenDescription']."<br />\n";
 //    echo $listing['RoomCount']."<br />\n";
 //    echo $listing['ThreeQtrBaths']."<br />\n";
 //    echo $listing['UtilityInformation']."<br />\n";
 //    echo $listing['WasherIncluded']."<br />\n";
 //    echo $listing['WasherDryerLocation']."<br />\n";
 //    echo $listing['Water']."<br />\n";
 //    echo $listing['Area']."<br />\n";
 //    echo $listing['CommunityName']."<br />\n";
 //    echo $listing['ElementarySchool35']."<br />\n";
 //    echo $listing['ElementarySchoolK2']."<br />\n";
	// echo $listing['CommunityName']."<br />\n";
	// echo $listing['HighSchool']."<br />\n";
	// echo $listing['HouseFaces']."<br />\n";
	// echo $listing['JrHighSchool']."<br />\n";
	// echo $listing['ParcelNumber']."<br />\n";
	// echo $listing['StreetNumberNumeric']."<br />\n";
	// echo $listing['SubdivisionName']."<br />\n";
	// echo $listing['SubdivisionNumber']."<br />\n";
	// echo $listing['SubdivisionNumSearch']."<br />\n";
	// echo $listing['TaxDistrict']."<br />\n";

     //$photos = $rets->GetObject("Property", "Photo", $listing['Matrix_Unique_ID'], "*", 0);
     // echo '<pre>';
     // print_r($photos);
     // echo '</pre>';
// foreach ($photos as $photo) {
//         $Content_id = $photo['Content-ID'];
//         $Object_id = $photo['Object-ID'];
//         $contentType = $photo['Content-Type'];
// 			    if ($photo['Success'] == 1) {
// 			        $imagebase64 = base64_encode($photo['Data']); 
// 			        $property_image =$photo['Data'];
// 			        $imagstr = $contentType.';base64,'.$imagebase64."<br />\n";
// 					$new_data = explode(";",$imagstr);
// 					$type = $new_data[0];
// 					$data = explode(",",$new_data[1]);
// 					header("Content-type:".$type);
// 					echo base64_decode($data[1]);

// 			    }
			   

//}

//echo $i.$listing['City']."<br />\n";
	
}
if($property)
{
	echo 'done';
}

//$search_result=array();
//$key=0;
// while ($listing = $rets->FetchRow($results)) {
// 	$search_result[$key]['Matrix_Unique_ID']=$listing['Matrix_Unique_ID'];
// 	$search_result[$key]['StreetNumber']=$listing['StreetNumber'];
// 	$search_result[$key]['StreetName']=$listing['StreetName'];
// 	$search_result[$key]['City']=$listing['City'];
// 	$search_result[$key]['Area']=$listing['Area'];
// 	$search_result[$key]['StateOrProvince']=$listing['StateOrProvince'];
// 	$search_result[$key]['SqFtTotal']=$listing['SqFtTotal'];
// 	$search_result[$key]['Status']=$listing['Status'];
// 	$search_result[$key]['PublicAddress']=$listing['PublicAddress'];
// 	$search_result[$key]['ListPrice']=$listing['ListPrice'];
// 	$search_result[$key]['BathsTotal']=$listing['BathsTotal'];
// 	$search_result[$key]['BathsHalf']=$listing['BathsHalf'];
// 	$search_result[$key]['BathsFull']=$listing['BathsFull'];
// 	$search_result[$key]['NumAcres']=$listing['NumAcres'];
// 	$search_result[$key]['BedroomsTotalPossibleNum']=$listing['BedroomsTotalPossibleNum'];
// 	$search_result[$key]['VirtualTourLink']=$listing['VirtualTourLink'];
// 	$search_result[$key]['PostalCode']=$listing['PostalCode'];
// 	$search_result[$key]['NumGAcres']=$listing['NumGAcres'];
//   $search_result[$key]['PhotoCount']=$listing['PhotoCount'];
//   $search_result[$key]['MLSNumber']=$listing['MLSNumber'];
//   $search_result[$key]['OriginalEntryTimestamp']=$listing['OriginalEntryTimestamp'];
//   $search_result[$key]['CommunityName']=$listing['CommunityName'];
	
	
//    $photos = $rets->GetObject("Property", "Photo", $listing['Matrix_Unique_ID'], "1", 0);
// foreach ($photos as $photo) {
//         $listing = $photo['Content-ID'];
//         $number = $photo['Object-ID'];
//     if ($photo['Success'] == true) {
//         $contentType = $photo['Content-Type'];
//         $base64 = base64_encode($photo['Data']); 
//         //echo "<img src='data:{$contentType};base64,{$base64}' />";
//         //$image_link='data:{$contentType};base64,{$base64}';
//         $search_result[$key]['contentType']=$photo['Content-Type'];
//         $search_result[$key]['property_image']=$photo['Data'];
//     }
//     else {
//         //echo "({$listing}-{$number}): {$photo['ReplyCode']} = {$photo['ReplyText']}\n";
//     }
//   }
//   $key++;
// }

//dd($search_result);



		        

		}
		else {
		        echo "  + Not connected";
		        // echo "<pre>";
		        // print_r($rets->Error());
		        // exit;
		}


    }
}
