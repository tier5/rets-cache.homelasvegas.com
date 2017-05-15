<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phRETS;
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

class SearchController extends Controller
{
    public function index()
    {
    	$data = array();
    	return view('rets.search', ['data'=>$data]);
    }

    public function test(){
   		/*$cityList = Citylist::orderBy('id','desc')->get();
   		 $this->dispatch(new Searchresultinsert());
   		dd($cityList);*/
   	echo $message='hello';
   //$this->dispatch(new InserSearchList());
  $job = (new InserSearchList());
  $this->dispatch($job);
           
    }
    public function do_search(Request $request,$offset)
    {
    	$search_result = array();
	    $data = array();
	    // $data['limit'] = '';
		// $data['offset_val'] = '';
		// $data['city'] = '';
    	$rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
		$rets_username = "neal";
		$rets_password = "glvar";

		$rets = new phRETS;

		$rets->AddHeader("RETS-Version", "RETS/1.7.2");

		$connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);

		if ($connect) 
		{
		        echo "  + Connected";
		        $city = $request->input('city');
		        
		        if($city!=""){
		        $data['city'] = $city;
				$query="(City={$city})";
    			}

    		
    			
			    $limit=$request->input('limit');
			  
			    $data['limit'] = $limit;
			   
				// if(isset($offset)){
				//   $start_page=($offset-1)*$limit;
				// }else{
				//   $offset=0;
				// }


			    if($offset > 0)
			    {
			    	$data['offset_val'] = $offset;
			    }

			    $data['count']=$data['offset_val']+$data['limit'];
			    // else
			    // {
			    // 	$data['offset_val'] = $offset;
			    // }


				 $search = $rets->SearchQuery("Property","Listing",$query,array("StandardNames" => 0,'Limit' =>$limit,'Offset'=>$offset));
				 //echo '<pre>';
				 //print_r($rets->FetchRow($search));exit;
					$result_count=$rets->TotalRecordsFound();
					//echo $result_count; exit;
					$total_records= $pages=ceil($result_count/$limit);
					$search_result=array();
					$key=0;
				while ($listing = $rets->FetchRow($search)) 
				{   
                      /*echo '<pre>';
					print_r($listing);
					 echo '</pre>';exit;*/
					$search_result[$key]['Matrix_Unique_ID']=$listing['Matrix_Unique_ID'];
					$search_result[$key]['StreetNumber']=$listing['StreetNumber'];
					$search_result[$key]['StreetName']=$listing['StreetName'];
					$search_result[$key]['City']=$listing['City'];
					$search_result[$key]['Area']=$listing['Area'];
					$search_result[$key]['StateOrProvince']=$listing['StateOrProvince'];
					$search_result[$key]['SqFtTotal']=$listing['SqFtTotal'];
					$search_result[$key]['Status']=$listing['Status'];
					$search_result[$key]['PublicAddress']=$listing['PublicAddress'];
					$search_reuslt[$key]['PublicAddressYN'] = $listing['PublicAddressYN'];
                    $search_reuslt[$key]['PublicRemarks'] = $listing['PublicRemarks'];
                    $search_reuslt[$key]['ListAgentMLSID'] = $listing['ListAgentMLSID'];
                    $search_reuslt[$key]['ListAgentFullName'] = $listing['ListAgentFullName'];
                    $search_reuslt[$key]['ListOfficeName'] = $listing['ListOfficeName'];
                    $search_reuslt[$key]['ListAgentDirectWorkPhone'] = $listing['ListAgentDirectWorkPhone'];
					$search_result[$key]['ListPrice']=$listing['ListPrice'];
					$search_result[$key]['BathsTotal']=$listing['BathsTotal'];
					$search_result[$key]['BathsHalf']=$listing['BathsHalf'];
					$search_result[$key]['BathsFull']=$listing['BathsFull'];
					$search_result[$key]['NumAcres']=$listing['NumAcres'];
					$search_result[$key]['BedroomsTotalPossibleNum']=$listing['BedroomsTotalPossibleNum'];
					$search_result[$key]['VirtualTourLink']=$listing['VirtualTourLink'];
					$search_result[$key]['PostalCode']=$listing['PostalCode'];
					$search_result[$key]['NumGAcres']=$listing['NumGAcres'];
				    $search_result[$key]['PhotoCount']=$listing['PhotoCount'];
				    $search_result[$key]['MLSNumber']=$listing['MLSNumber'];
				    $search_result[$key]['OriginalEntryTimestamp']=$listing['OriginalEntryTimestamp'];
				    $search_result[$key]['CommunityName']=$listing['CommunityName'];
	
	
   				$photos = $rets->GetObject("Property", "Photo", $listing['Matrix_Unique_ID'], "*", 0);
               //dd($photos);exit;

   				$deleteImage = PropertyImage::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->delete();
   				$contentType = $property_image  ='';
   				$content_id = $object_id = $Success = 0;
				foreach ($photos as $photo) {
					
					if(isset($photo['Content-ID']) && $photo['Content-ID']!='')
					{
						$content_id = $photo['Content-ID'];
					}

					if(isset($photo['Object-ID']) && $photo['Object-ID']!='')
					{
						$object_id = $photo['Object-ID'];
					}

					if(isset($photo['Success']) && $photo['Success']!='')
					{
						$Success = $photo['Success'];
					}

				    if ($photo['Success'] == true && isset($photo['Content-Type']) && $photo['Content-Type']!=''){
				        $contentType = $photo['Content-Type'];
				        $property_image = base64_encode($photo['Data']); 
				        $search_result[$key]['contentType']=$photo['Content-Type'];
				        $search_result[$key]['property_image']=$photo['Data'];
				    }
				    else
				    {
				    	$search_result[$key]['contentType']='';
				        $search_result[$key]['property_image']='';
				    }



				    if(isset($listing['Content-Description']) && $listing['Content-Description'] != '')
				    		{
				    			$ContentDescription = $listing['Content-Description'];
				    		}
				    		else
				    		{
				    			$ContentDescription = '';
				    		}
				   
				    		
				    		 $propertyimage =new PropertyImage();
						     $propertyimage->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
						     $propertyimage->MLSNumber = $listing['MLSNumber'];
						     $propertyimage->ContentId = $content_id;
						     $propertyimage->ObjectId  = $object_id;
						     $propertyimage->Success   = $Success;
						     $propertyimage->ContentType = $contentType;
						     $propertyimage->Encoded_image = $property_image;
						     $propertyimage->ContentDesc = $ContentDescription;
						     $propertyimage->save();
				       
				     
				    
 				 }


 				                

					              if(isset($listing['BathsHalf']) && $listing['BathsHalf']!= '') 
					              {
					              	$BathsHalf = $listing['BathsHalf'];
					              }
					              else
					              {
					              		$BathsHalf = 0;
					              }

					              if(isset($listing['BathsFull']) && $listing['BathsFull']!= '') 
					              {
					              	$BathsFull = $listing['BathsFull'];
					              }
					              else
					              {
					              		$BathsFull = 0;
					              }

					             

					              if(isset($listing['SqFtTotal']) && $listing['SqFtTotal']!= '') 
					              {
					              	$SqFtTotal = $listing['SqFtTotal'];
					              }
					              else
					              {
					              	$SqFtTotal = 0;
					              }

					              if(isset($listing['BathsTotal']) && $listing['BathsTotal']!= '') 
					              {
					              	$BathsTotal = $listing['BathsTotal'];
					              }
					              else
					              {
					              	$BathsTotal = 0;
					              }


					              if(isset($listing['NumAcres']) && $listing['NumAcres']!= '') 
					              {
					              	 $NumAcres = $listing['NumAcres'];
					              }
					              else
					              {
					              	 $NumAcres = 0;
					              }

					              if(isset($listing['YearBuilt']) && $listing['YearBuilt']!= '') 
					              {
					              	$YearBuilt = $listing['YearBuilt'];
					              }
					              else
					              {
					              	$YearBuilt = 0;
					              }

					              if(isset($listing['Garage']) && $listing['Garage']!= '') 
					              {
					              	$Garage = $listing['Garage'];
					              }
					              else
					              {
					              	$Garage = 0;
					              }

					              if(isset($listing['LotSqft']) && $listing['LotSqft']!= '') 
					              {
					              	$LotSqft = $listing['LotSqft'];
					              }
					              else
					              {
					              	$LotSqft = 0;
					              }


					              if(isset($listing['Assessments']) && $listing['Assessments']!= '') 
					              {
					              	$Assessments = $listing['Assessments'];
					              }
					              else
					              {
					              	$Assessments = 0;
					              }

					              if(isset($listing['YearRoundSchoolYN']) && $listing['YearRoundSchoolYN']!= '') 
					              {
					              	$YearRoundSchoolYN = $listing['YearRoundSchoolYN'];
					              }
					              else
					              {
					              	$YearRoundSchoolYN = 0;
					              }

					              if(isset($listing['RefrigeratorYN']) && $listing['RefrigeratorYN']!= '') 
					              {
					              	$RefrigeratorYN = $listing['RefrigeratorYN'];
					              }
					              else
					              {
					              	$RefrigeratorYN = 0;
					              }

					              if(isset($listing['RealtorYN']) && $listing['RealtorYN']!= '') 
					              {
					              	$RealtorYN = $listing['RealtorYN'];
					              }
					              else
					              {
					              	$RealtorYN = 0;
					              }

					              if(isset($listing['GreenBuildingCertificationYN']) && $listing['GreenBuildingCertificationYN']!= '') 
					              {
					              	$GreenBuildingCertificationYN = $listing['GreenBuildingCertificationYN'];
					              }
					              else
					              {
					              	$GreenBuildingCertificationYN = 0;
					              }

					              if(isset($listing['GatedYN']) && $listing['GatedYN']!= '') 
					              {
					              	$GatedYN = $listing['GatedYN'];
					              }
					              else
					              {
					              	$GatedYN = 0;
					              }

					              if(isset($listing['CourtApproval']) && $listing['CourtApproval']!= '') 
					              {
					              	$CourtApproval = $listing['CourtApproval'];
					              }
					              else
					              {
					              	$CourtApproval = 0;
					              }

					              // ------------------------------------------

					               if(isset($listing['AnnualPropertyTaxes']) && $listing['AnnualPropertyTaxes']!= '') 
					              {
					              	$AnnualPropertyTaxes = $listing['AnnualPropertyTaxes'];
					              }
					              else
					              {
					              	$AnnualPropertyTaxes = 0;
					              }
					              
					              if(isset($listing['AppxAssociationFee']) && $listing['AppxAssociationFee']!= '') 
					              {
					              	$AppxAssociationFee = $listing['AppxAssociationFee'];
					              }
					              else
					              {
					              	$AppxAssociationFee = 0;
					              }

					              if(isset($listing['AssociationFee1']) && $listing['AssociationFee1']!= '') 
					              {
					              	$AssociationFee1 = $listing['AssociationFee1'];
					              }
					              else
					              {
					              	$AssociationFee1 = 0;
					              }

					              if(isset($listing['AVMYN']) && $listing['AVMYN']!= '') 
					              {
					              	$AVMYN = $listing['AVMYN'];
					              }
					              else
					              {
					              	$AVMYN = 0;
					              }

					              if(isset($listing['ForeclosureCommencedYN']) && $listing['ForeclosureCommencedYN']!= '') 
					              {
					              	$ForeclosureCommencedYN = $listing['ForeclosureCommencedYN'];
					              }
					              else
					              {
					              	$ForeclosureCommencedYN = 0;
					              }

					              if(isset($listing['EarnestDeposit']) && $listing['EarnestDeposit']!= '') 
					              {
					              	$EarnestDeposit = $listing['EarnestDeposit'];
					              }
					              else
					              {
					              	$EarnestDeposit = 0;
					              }


					              if(isset($listing['MasterPlanFeeAmount']) && $listing['MasterPlanFeeAmount']!= '') 
					              {
					              	$MasterPlanFeeAmount = $listing['MasterPlanFeeAmount'];
					              }
					              else
					              {
					              	$MasterPlanFeeAmount = 0;
					              }

					              if(isset($listing['RepoReoYN']) && $listing['RepoReoYN']!= '') 
					              {
					              	$RepoReoYN = $listing['RepoReoYN'];
					              }
					              else
					              {
					              	$RepoReoYN = 0;
					              }

					              if(isset($listing['ShortSale']) && $listing['ShortSale']!= '') 
					              {
					              	$ShortSale = $listing['ShortSale'];
					              }
					              else
					              {
					              	$ShortSale = 0;
					              }

					              if(isset($listing['SIDLIDYN']) && $listing['SIDLIDYN']!= '') 
					              {
					              	$SIDLIDYN = $listing['SIDLIDYN'];
					              }
					              else
					              {
					              	$SIDLIDYN = 0;
					              }

					              //------------------------------------------
					              
					              if(isset($listing['ApproxTotalLivArea']) && $listing['ApproxTotalLivArea']!= '') 
					              {
					              	$ApproxTotalLivArea = $listing['ApproxTotalLivArea'];
					              }
					              else
					              {
					              	$ApproxTotalLivArea = 0;
					              }

					              if(isset($listing['BathDownYN']) && $listing['BathDownYN']!= '') 
					              {
					              	$BathDownYN = $listing['BathDownYN'];
					              }
					              else
					              {
					              	$BathDownYN = 0;
					              }

					              if(isset($listing['BedroomDownstairsYN']) && $listing['BedroomDownstairsYN']!= '') 
					              {
					              	$BedroomDownstairsYN = $listing['BedroomDownstairsYN'];
					              }
					              else
					              {
					              	$BedroomDownstairsYN = 0;
					              }

					              if(isset($listing['BedroomsTotalPossibleNum']) && $listing['BedroomsTotalPossibleNum']!= '') 
					              {
					              	$BedroomsTotalPossibleNum = $listing['BedroomsTotalPossibleNum'];
					              }
					              else
					              {
					              	$BedroomsTotalPossibleNum = 0;
					              }

					              if(isset($listing['DishwasherYN']) && $listing['DishwasherYN']!= '') 
					              {
					              	$DishwasherYN = $listing['DishwasherYN'];
					              }
					              else
					              {
					              	$DishwasherYN = 0;
					              }

					              if(isset($listing['DisposalYN']) && $listing['DisposalYN']!= '') 
					              {
					              	$DisposalYN = $listing['DisposalYN'];
					              }
					              else
					              {
					              	$DisposalYN = 0;
					              }

					              if(isset($listing['DryerIncluded']) && $listing['DryerIncluded']!= '') 
					              {
					              	$DryerIncluded = $listing['DryerIncluded'];
					              }
					              else
					              {
					              	$DryerIncluded = 0;
					              }

					              if(isset($listing['Fireplaces']) && $listing['Fireplaces']!= '') 
					              {
					              	$Fireplaces = $listing['Fireplaces'];
					              }
					              else
					              {
					              	$Fireplaces = 0;
					              }

					              if(isset($listing['NumDenOther']) && $listing['NumDenOther']!= '') 
					              {
					              	$NumDenOther = $listing['NumDenOther'];
					              }
					              else
					              {
					              	$NumDenOther = 0;
					              }

					              if(isset($listing['RoomCount']) && $listing['RoomCount']!= '') 
					              {
					              	$RoomCount = $listing['RoomCount'];
					              }
					              else
					              {
					              	$RoomCount = 0;
					              }

					              if(isset($listing['ThreeQtrBaths']) && $listing['ThreeQtrBaths']!= '') 
					              {
					              	$ThreeQtrBaths = $listing['ThreeQtrBaths'];
					              }
					              else
					              {
					              	$ThreeQtrBaths = 0;
					              }

					              if(isset($listing['WasherIncluded']) && $listing['WasherIncluded']!= '') 
					              {
					              	$WasherIncluded = $listing['WasherIncluded'];
					              }
					              else
					              {
					              	$WasherIncluded = 0;
					              }
					              
					              //-------------------------------------

					              if(isset($listing['StreetNumberNumeric']) && $listing['StreetNumberNumeric']!= '') 
					              {
					              	$StreetNumberNumeric = $listing['StreetNumberNumeric'];
					              }
					              else
					              {
					              	$StreetNumberNumeric = 0;
					              }

					              if(isset($listing['SubdivisionNumber']) && $listing['SubdivisionNumber']!= '') 
					              {
					              	$SubdivisionNumber = $listing['SubdivisionNumber'];
					              }
					              else
					              {
					              	$SubdivisionNumber = 0;
					              }


					              if(isset($listing['ConvertedGarageYN']) && $listing['ConvertedGarageYN']!= '') 
					              {
					              	$ConvertedGarageYN = $listing['ConvertedGarageYN'];
					              }
					              else
					              {
					              	$ConvertedGarageYN = 0;
					              }


					              if(isset($listing['PvPool']) && $listing['PvPool']!= '') 
					              {
					              	$PvPool = $listing['PvPool'];
					              }
					              else
					              {
					              	$PvPool = 0;
					              }


					              if(isset($listing['AgeRestrictedCommunityYN']) && $listing['AgeRestrictedCommunityYN']!= '') 
					              {
					              	$AgeRestrictedCommunityYN = $listing['AgeRestrictedCommunityYN'];
					              }
					              else
					              {
					              	$AgeRestrictedCommunityYN = 0;
					              }

					              if(isset($listing['RATIO_CurrentPrice_By_SQFT']) && $listing['RATIO_CurrentPrice_By_SQFT']!= '') 
					              {
					              	$RATIO_CurrentPrice_By_SQFT = $listing['RATIO_CurrentPrice_By_SQFT'];
					              }
					              else
					              {
					              	$RATIO_CurrentPrice_By_SQFT = 0;
					              }

					              if(isset($listing['CurrentPrice']) && $listing['CurrentPrice']!= '') 
					              {
					              	$CurrentPrice = $listing['CurrentPrice'];
					              }
					              else
					              {
					              	$CurrentPrice = 0;
					              }

					              
					              

					              


					              

					              
					              
                                 //-------------------------------------

					              	$is_property = PropertyDetail::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();


							                 if ($is_property) 
							                 {
								    	 	   
									           $is_property->ListPrice 		        = $listing['ListPrice'];
									           $is_property->Status 		        = $listing['Status'];
									           $is_property->BedroomsTotalPossibleNum = $BedroomsTotalPossibleNum; 
									           $is_property->BathsTotal= $BathsTotal; 
									           $is_property->BathsHalf = $BathsHalf; 
									           $is_property->BathsFull = $BathsFull; 
									           $is_property->NumAcres  = $NumAcres; 
									           $is_property->SqFtTotal = $SqFtTotal; 
									           $is_property->StreetNumber = $listing['StreetNumber']; 
									           $is_property->StreetName   = $listing['StreetName']; 
									           $is_property->City         = $data['city'];
									           $is_property->MLSNumber    = $listing['MLSNumber'];
								    	       $is_property->save();





										    }
											else
											{

											   $property =new PropertyDetail();

								    	 	   $property->Matrix_Unique_ID 		= $listing['Matrix_Unique_ID'];
									           $property->ListPrice 		    = $listing['ListPrice'];
									           $property->Status 		        = $listing['Status'];
									           $property->BedroomsTotalPossibleNum = $BedroomsTotalPossibleNum; 
									           $property->BathsTotal= $BathsTotal; 
									           $property->BathsHalf = $BathsHalf; 
									           $property->BathsFull = $BathsFull; 
									           $property->NumAcres  =$NumAcres; 
									           $property->SqFtTotal = $SqFtTotal; 
									           $property->StreetNumber = $listing['StreetNumber']; 
									           $property->StreetName   = $listing['StreetName']; 
									           $property->City         = $data['city'];
									           $property->MLSNumber    = $listing['MLSNumber'];
								    	       $property->save();
					                        }




					                        $is_property_feature = PropertyFeature::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();


							                 if ($is_property_feature) 
							                 {
								    	 	   
									           $is_property_feature->YearBuilt = $YearBuilt;
									           $is_property_feature->PropertyType = $listing['PropertyType'];
									           $is_property_feature->PropertySubType  = $listing['PropertySubType']; 
									           $is_property_feature->CountyOrParish = $listing['CountyOrParish']; 
									           $is_property_feature->Zoning = $listing['Zoning']; 
									           $is_property_feature->MLSNumber    = $listing['MLSNumber'];
								    	       $is_property->save();


										    }
											else
											{

											   $propertyfeature =new PropertyFeature();

								    	 	   $propertyfeature->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
									           $propertyfeature->YearBuilt        = $YearBuilt;
									           $propertyfeature->PropertyType     = $listing['PropertyType'];
									           $propertyfeature->PropertySubType  = $listing['PropertySubType']; 
									           $propertyfeature->CountyOrParish   = $listing['CountyOrParish']; 
									           $propertyfeature->Zoning           = $listing['Zoning']; 
									           $propertyfeature->MLSNumber        = $listing['MLSNumber'];
								    	       $propertyfeature->save();
					                        }


					                        $is_property_external_feature = PropertyExternalFeature::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();


							                 if ($is_property_external_feature) 
							                 {
								    	 	   
								    	 	   $is_property_external_feature->MLSNumber    = $listing['MLSNumber'];
									           $is_property_external_feature->BuildingDescription = $listing['BuildingDescription'];
									           $is_property_external_feature->BuiltDescription = $listing['BuiltDescription'];
									           $is_property_external_feature->ConstructionDescription  = $listing['ConstructionDescription']; 
									           $is_property_external_feature->ConvertedGarageYN = $ConvertedGarageYN; 
									           $is_property_external_feature->EquestrianDescription = $listing['EquestrianDescription']; 


									           $is_property_external_feature->Fence = $listing['Fence'];
									           $is_property_external_feature->FenceType = $listing['FenceType'];
									           $is_property_external_feature->Garage = $Garage;
									           $is_property_external_feature->GarageDescription = $listing['GarageDescription'];
									           $is_property_external_feature->HouseViews = $listing['HouseViews'];
									           $is_property_external_feature->LandscapeDescription = $listing['LandscapeDescription'];
									           $is_property_external_feature->LotDescription = $listing['LotDescription'];
									           $is_property_external_feature->LotSqft = $LotSqft;
									           $is_property_external_feature->ParkingDescription = $listing['ParkingDescription'];

									           $is_property_external_feature->PoolDescription = $listing['PoolDescription'];
									           $is_property_external_feature->PvPool = $PvPool;
									           $is_property_external_feature->RoofDescription = $listing['RoofDescription'];
									           $is_property_external_feature->Sewer = $listing['Sewer'];
									           $is_property_external_feature->SolarElectric = $listing['SolarElectric'];
									           $is_property_external_feature->Type = $listing['Type'];
									           $is_property_external_feature->BuiltDescription = $listing['BuiltDescription'];
									           $is_property_external_feature->ParkingDescription = $listing['ParkingDescription'];
									           $is_property_external_feature->ParkingDescription = $listing['ParkingDescription'];
									           $is_property_external_feature->ParkingDescription = $listing['ParkingDescription'];
									           $is_property_external_feature->ParkingDescription = $listing['ParkingDescription'];
									           
								    	       $is_property_external_feature->save();


										    }
											else
											{

											   $propertyexternalfeature =new PropertyExternalFeature();

								    	 	   $propertyexternalfeature->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
								    	 	   $propertyexternalfeature->MLSNumber    = $listing['MLSNumber'];
									           $propertyexternalfeature->BuildingDescription = $listing['BuildingDescription'];
									           $propertyexternalfeature->BuiltDescription = $listing['BuiltDescription'];
									           $propertyexternalfeature->ConstructionDescription  = $listing['ConstructionDescription']; 
									           $propertyexternalfeature->ConvertedGarageYN = $ConvertedGarageYN; 
									           $propertyexternalfeature->EquestrianDescription = $listing['EquestrianDescription']; 


									           $propertyexternalfeature->Fence = $listing['Fence'];
									           $propertyexternalfeature->FenceType = $listing['FenceType'];
									           $propertyexternalfeature->Garage = $Garage;
									           $propertyexternalfeature->GarageDescription = $listing['GarageDescription'];
									           $propertyexternalfeature->HouseViews = $listing['HouseViews'];
									           $propertyexternalfeature->LandscapeDescription = $listing['LandscapeDescription'];
									           $propertyexternalfeature->LotDescription = $listing['LotDescription'];
									           $propertyexternalfeature->LotSqft = $LotSqft;
									           $propertyexternalfeature->ParkingDescription = $listing['ParkingDescription'];

									           $propertyexternalfeature->PoolDescription = $listing['PoolDescription'];
									           $propertyexternalfeature->PvPool = $PvPool;
									           $propertyexternalfeature->RoofDescription = $listing['RoofDescription'];
									           $propertyexternalfeature->Sewer = $listing['Sewer'];
									           $propertyexternalfeature->SolarElectric = $listing['SolarElectric'];
									           $propertyexternalfeature->Type = $listing['Type'];
									           $propertyexternalfeature->BuiltDescription = $listing['BuiltDescription'];
									           $propertyexternalfeature->ParkingDescription = $listing['ParkingDescription'];
									           $propertyexternalfeature->ParkingDescription = $listing['ParkingDescription'];
									           $propertyexternalfeature->ParkingDescription = $listing['ParkingDescription'];
									           $propertyexternalfeature->ParkingDescription = $listing['ParkingDescription'];
									           
								    	       $propertyexternalfeature->save();
					                        }



					                        $is_property_additional = PropertyAdditional::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();


							                 if ($is_property_additional) 
							                 {
								    	 	   
								    	 	   $is_property_additional->MLSNumber    = $listing['MLSNumber'];
									           $is_property_additional->AgeRestrictedCommunityYN = $AgeRestrictedCommunityYN;
									           $is_property_additional->Assessments = $Assessments;
									           $is_property_additional->AssociationFeaturesAvailable  = $listing['AssociationFeaturesAvailable']; 
									           $is_property_additional->AssociationFeeIncludes = $listing['AssociationFeeIncludes']; 
									           $is_property_additional->AssociationName = $listing['AssociationName']; 

									           $is_property_additional->Builder = $listing['Builder'];
									           $is_property_additional->CensusTract = $listing['CensusTract'];
									           $is_property_additional->CourtApproval = $CourtApproval;

									           $is_property_additional->GatedYN = $GatedYN;

									           $is_property_additional->GreenBuildingCertificationYN = $GreenBuildingCertificationYN;
									           $is_property_additional->BathsHalf = $BathsHalf;
									           $is_property_additional->ListingAgreementType = $listing['ListingAgreementType'];
									           $is_property_additional->Litigation = $listing['Litigation'];
									           $is_property_additional->MasterPlanFeeMQYN = $listing['MasterPlanFeeMQYN'];
									           $is_property_additional->MiscellaneousDescription = $listing['MiscellaneousDescription'];

									           $is_property_additional->Model = $listing['Model'];
									           $is_property_additional->OwnerLicensee = $listing['OwnerLicensee'];
									           $is_property_additional->Ownership = $listing['Ownership'];
									           $is_property_additional->PoweronorOff = $listing['PoweronorOff'];
									           $is_property_additional->PropertyDescription = $listing['PropertyDescription'];

									           $is_property_additional->PropertySubType = $listing['PropertySubType'];

									           $is_property_additional->PublicAddress = $listing['PublicAddress'];
									           $is_property_additional->PublicRemarks = $listing['PublicRemarks'];
                                               $is_property_additional->ListAgentMLSID = $listing['ListAgentMLSID'];
                                               $is_property_additional->ListAgentFullName = $listing['ListAgentFullName'];
									           $is_property_additional->RealtorYN = $RealtorYN;
									           $is_property_additional->RefrigeratorYN = $RefrigeratorYN;
									           $is_property_additional->Spa = $listing['Spa'];
									           $is_property_additional->SpaDescription = $listing['SpaDescription'];
									           $is_property_additional->YearRoundSchoolYN = $YearRoundSchoolYN;

								    	       $is_property_additional->save();


										    }
											else
											{

											   $propertyadditional =new PropertyAdditional();

								    	 	   $propertyadditional->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
								    	 	   $propertyadditional->MLSNumber    = $listing['MLSNumber'];
									           $propertyadditional->AgeRestrictedCommunityYN = $AgeRestrictedCommunityYN;
									           $propertyadditional->Assessments = $Assessments;
									           $propertyadditional->AssociationFeaturesAvailable  = $listing['AssociationFeaturesAvailable']; 
									           $propertyadditional->AssociationFeeIncludes = $listing['AssociationFeeIncludes']; 
									           $propertyadditional->AssociationName = $listing['AssociationName']; 

									           $propertyadditional->Builder = $listing['Builder'];
									           $propertyadditional->CensusTract = $listing['CensusTract'];
									           $propertyadditional->CourtApproval = $CourtApproval;

									           $propertyadditional->GatedYN = $GatedYN;

									           $propertyadditional->GreenBuildingCertificationYN = $GreenBuildingCertificationYN;
									           $propertyadditional->BathsHalf = $BathsHalf;
									           $propertyadditional->ListingAgreementType = $listing['ListingAgreementType'];
									           $propertyadditional->Litigation = $listing['Litigation'];
									           $propertyadditional->MasterPlanFeeMQYN = $listing['MasterPlanFeeMQYN'];
									           $propertyadditional->MiscellaneousDescription = $listing['MiscellaneousDescription'];

									           $propertyadditional->Model = $listing['Model'];
									           $propertyadditional->OwnerLicensee = $listing['OwnerLicensee'];
									           $propertyadditional->Ownership = $listing['Ownership'];
									           $propertyadditional->PoweronorOff = $listing['PoweronorOff'];
									           $propertyadditional->PropertyDescription = $listing['PropertyDescription'];

									           $propertyadditional->PropertySubType = $listing['PropertySubType'];

									           $propertyadditional->PublicAddress = $listing['PublicAddress'];
									           $propertyadditional->PublicAddressYN = $listing['PublicAddressYN'];
                                               $propertyadditional->PublicRemarks = $listing['PublicRemarks'];
                                               $propertyadditional->ListAgentMLSID = $listing['ListAgentMLSID'];
                                               $propertyadditional->ListAgentFullName = $listing['ListAgentFullName'];
                                               $is_property_additional->ListOfficeName = $listing['ListOfficeName'];
                                               $is_property_additional->ListAgentDirectWorkPhone = $listing['ListAgentDirectWorkPhone'];
                                               $propertyadditional->ListOfficeName = $listing['ListOfficeName'];
                                               $propertyadditional->ListAgentDirectWorkPhone = $listing['ListAgentDirectWorkPhone'];
									           $propertyadditional->RealtorYN = $RealtorYN;
									           $propertyadditional->RefrigeratorYN = $RefrigeratorYN;
									           $propertyadditional->Spa = $listing['Spa'];
									           $propertyadditional->SpaDescription = $listing['SpaDescription'];
									           $propertyadditional->YearRoundSchoolYN = $YearRoundSchoolYN;

								    	       $propertyadditional->save();
					                        }

					                        $is_property_financial_detail = PropertyFinancialDetail::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();


							                 if ($is_property_financial_detail) 
							                 {
								    	 	   
								    	 	    $is_property_financial_detail->MLSNumber    = $listing['MLSNumber'];
									           $is_property_financial_detail->AnnualPropertyTaxes = $AnnualPropertyTaxes;
									           $is_property_financial_detail->AppxAssociationFee = $AppxAssociationFee;
									           $is_property_financial_detail->AssociationFee1  = $AssociationFee1; 
									           $is_property_financial_detail->AssociationFee1MQYN = $listing['AssociationFee1MQYN']; 
									           $is_property_financial_detail->AVMYN = $AVMYN; 

									           $is_property_financial_detail->CurrentPrice = $CurrentPrice; 

									           $is_property_financial_detail->EarnestDeposit = $EarnestDeposit; 
									           $is_property_financial_detail->FinancingConsidered = $listing['FinancingConsidered']; 
									           $is_property_financial_detail->ForeclosureCommencedYN = $ForeclosureCommencedYN; 
									           $is_property_financial_detail->MasterPlanFeeAmount = $MasterPlanFeeAmount; 
									           $is_property_financial_detail->RATIO_CurrentPrice_By_SQFT = $RATIO_CurrentPrice_By_SQFT; 
									          

									          $is_property_financial_detail->RepoReoYN = $RepoReoYN; 
									          $is_property_financial_detail->ShortSale = $ShortSale; 
									          $is_property_financial_detail->SIDLIDYN = $SIDLIDYN; 

									          
								    	       $is_property_financial_detail->save();


										    }
											else
											{

											   $propertyfinancialdetail =new PropertyFinancialDetail();

								    	 	   $propertyfinancialdetail->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
								    	 	    $propertyfinancialdetail->MLSNumber    = $listing['MLSNumber'];
									           $propertyfinancialdetail->AnnualPropertyTaxes = $AnnualPropertyTaxes;
									           $propertyfinancialdetail->AppxAssociationFee = $AppxAssociationFee;
									           $propertyfinancialdetail->AssociationFee1  = $AssociationFee1; 
									           $propertyfinancialdetail->AssociationFee1MQYN = $listing['AssociationFee1MQYN']; 
									           $propertyfinancialdetail->AVMYN = $AVMYN; 

									           $propertyfinancialdetail->CurrentPrice = $CurrentPrice; 

									           $propertyfinancialdetail->EarnestDeposit = $EarnestDeposit; 
									           $propertyfinancialdetail->FinancingConsidered = $listing['FinancingConsidered']; 
									           $propertyfinancialdetail->ForeclosureCommencedYN = $ForeclosureCommencedYN; 
									           $propertyfinancialdetail->MasterPlanFeeAmount = $MasterPlanFeeAmount; 
									           $propertyfinancialdetail->RATIO_CurrentPrice_By_SQFT = $RATIO_CurrentPrice_By_SQFT; 
									          

									          $propertyfinancialdetail->RepoReoYN = $RepoReoYN; 
									          $propertyfinancialdetail->ShortSale = $ShortSale; 
									          $propertyfinancialdetail->SIDLIDYN = $SIDLIDYN; 

								    	       $propertyfinancialdetail->save();
					                        }


					                         $is_property_interior_feature = PropertyInteriorFeature::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();


							                 if ($is_property_interior_feature) 
							                 {
								    	 	   
								    	 	    $is_property_interior_feature->MLSNumber    = $listing['MLSNumber'];
									           $is_property_financial_detail->ApproxTotalLivArea = $ApproxTotalLivArea;
									           $is_property_financial_detail->BathDownstairsDescription = $listing['BathDownstairsDescription'];
									           $is_property_financial_detail->BathDownYN  = $BathDownYN; 
									           $is_property_financial_detail->BedroomDownstairsYN = $BedroomDownstairsYN; 
									           $is_property_financial_detail->BedroomsTotalPossibleNum = $BedroomsTotalPossibleNum; 

									           $is_property_financial_detail->CoolingDescription = $listing['CoolingDescription']; 

									           $is_property_financial_detail->CoolingFuel = $listing['CoolingFuel']; 
									           $is_property_financial_detail->DishwasherYN = $DishwasherYN; 
									           $is_property_financial_detail->DisposalYN = $DisposalYN; 
									           $is_property_financial_detail->DryerIncluded = $DryerIncluded; 
									           $is_property_financial_detail->DryerUtilities = $listing['DryerUtilities']; 
									          

									          $is_property_financial_detail->EnergyDescription = $listing['EnergyDescription']; 
									          $is_property_financial_detail->FireplaceDescription = $listing['FireplaceDescription']; 
									          $is_property_financial_detail->FireplaceLocation = $listing['FireplaceLocation'];

									          $is_property_financial_detail->Fireplaces = $Fireplaces;
									          $is_property_financial_detail->FlooringDescription = $listing['FlooringDescription'];
									          $is_property_financial_detail->FurnishingsDescription = $listing['FurnishingsDescription'];
									          $is_property_financial_detail->HeatingDescription = $listing['HeatingDescription'];
									          $is_property_financial_detail->HeatingFuel = $listing['HeatingFuel'];
									          $is_property_financial_detail->Interior = $listing['Interior']; 

									          $is_property_financial_detail->NumDenOther = $NumDenOther; 
									          $is_property_financial_detail->OtherApplianceDescription = $listing['OtherApplianceDescription']; 
									          $is_property_financial_detail->OvenDescription = $listing['OvenDescription']; 
									          $is_property_financial_detail->RoomCount = $RoomCount; 

									          $is_property_financial_detail->ThreeQtrBaths = $ThreeQtrBaths; 
									          $is_property_financial_detail->UtilityInformation = $listing['UtilityInformation']; 
									          $is_property_financial_detail->WasherIncluded = $WasherIncluded; 
									          $is_property_financial_detail->WasherDryerLocation = $listing['WasherDryerLocation']; 
									          $is_property_financial_detail->Water = $listing['Water']; 

									          
								    	       $is_property_interior_feature->save();


										    }
											else
											{

											   $propertyfinancialdetail =new PropertyInteriorFeature();

								    	 	   $propertyfinancialdetail->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
								    	 	    $propertyfinancialdetail->MLSNumber    = $listing['MLSNumber'];
									           $propertyfinancialdetail->ApproxTotalLivArea = $ApproxTotalLivArea;
									           $propertyfinancialdetail->BathDownstairsDescription = $listing['BathDownstairsDescription'];
									           $propertyfinancialdetail->BathDownYN  = $BathDownYN; 
									           $propertyfinancialdetail->BedroomDownstairsYN = $BedroomDownstairsYN; 
									           $propertyfinancialdetail->BedroomsTotalPossibleNum = $BedroomsTotalPossibleNum; 

									           $propertyfinancialdetail->CoolingDescription = $listing['CoolingDescription']; 

									           $propertyfinancialdetail->CoolingFuel = $listing['CoolingFuel']; 
									           $propertyfinancialdetail->DishwasherYN = $DishwasherYN; 
									           $propertyfinancialdetail->DisposalYN = $DisposalYN; 
									           $propertyfinancialdetail->DryerIncluded = $DryerIncluded; 
									           $propertyfinancialdetail->DryerUtilities = $listing['DryerUtilities']; 
									          

									          $propertyfinancialdetail->EnergyDescription = $listing['EnergyDescription']; 
									          $propertyfinancialdetail->FireplaceDescription = $listing['FireplaceDescription']; 
									          $propertyfinancialdetail->FireplaceLocation = $listing['FireplaceLocation'];

									          $propertyfinancialdetail->Fireplaces = $Fireplaces;
									          $propertyfinancialdetail->FlooringDescription = $listing['FlooringDescription'];
									          $propertyfinancialdetail->FurnishingsDescription = $listing['FurnishingsDescription'];
									          $propertyfinancialdetail->HeatingDescription = $listing['HeatingDescription'];
									          $propertyfinancialdetail->HeatingFuel = $listing['HeatingFuel'];
									          $propertyfinancialdetail->Interior = $listing['Interior']; 

									          $propertyfinancialdetail->NumDenOther = $NumDenOther; 
									          $propertyfinancialdetail->OtherApplianceDescription = $listing['OtherApplianceDescription']; 
									          $propertyfinancialdetail->OvenDescription = $listing['OvenDescription']; 
									          $propertyfinancialdetail->RoomCount = $RoomCount; 

									          $propertyfinancialdetail->ThreeQtrBaths = $ThreeQtrBaths; 
									          $propertyfinancialdetail->UtilityInformation = $listing['UtilityInformation']; 
									          $propertyfinancialdetail->WasherIncluded = $WasherIncluded; 
									          $propertyfinancialdetail->WasherDryerLocation = $listing['WasherDryerLocation']; 
									          $propertyfinancialdetail->Water = $listing['Water']; 

									          
								    	       $propertyfinancialdetail->save();
					                        }

					                        $is_property_location = PropertyLocation::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();


							                 if ($is_property_location) 
							                 {
								    	 	   
									           $is_property_location->MLSNumber    = $listing['MLSNumber'];
									           $is_property_location->Area = $listing['Area'];
									           $is_property_location->CommunityName  = $listing['CommunityName']; 
									           $is_property_location->ElementarySchool35 = $listing['ElementarySchool35']; 
									           $is_property_location->ElementarySchoolK2 = $listing['ElementarySchoolK2'];

									           $is_property_location->HighSchool = $listing['HighSchool']; 
									           $is_property_location->HouseFaces = $listing['HouseFaces'];

									           $is_property_location->JrHighSchool = $listing['JrHighSchool']; 
									           $is_property_location->ParcelNumber = $listing['ParcelNumber']; 
									           $is_property_location->StreetNumberNumeric = $StreetNumberNumeric; 
									           $is_property_location->SubdivisionName = $listing['SubdivisionName'];

									           $is_property_location->SubdivisionNumber = $SubdivisionNumber;
									           $is_property_location->SubdivisionNumSearch = $listing['SubdivisionNumSearch'];
									           $is_property_location->TaxDistrict = $listing['TaxDistrict']; 
									           
								    	       $is_property_location->save();


										    }
											else
											{

											   $propertylocation =new PropertyLocation();
											   $propertylocation->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
									           $propertylocation->MLSNumber    = $listing['MLSNumber'];
									           $propertylocation->Area = $listing['Area'];
									           $propertylocation->CommunityName  = $listing['CommunityName']; 
									           $propertylocation->ElementarySchool35 = $listing['ElementarySchool35']; 
									           $propertylocation->ElementarySchoolK2 = $listing['ElementarySchoolK2'];

									           $propertylocation->HighSchool = $listing['HighSchool']; 
									           $propertylocation->HouseFaces = $listing['HouseFaces'];

									           $propertylocation->JrHighSchool = $listing['JrHighSchool']; 
									           $propertylocation->ParcelNumber = $listing['ParcelNumber']; 
									           $propertylocation->StreetNumberNumeric = $StreetNumberNumeric; 
									           $propertylocation->SubdivisionName = $listing['SubdivisionName'];

									           $propertylocation->SubdivisionNumber = $SubdivisionNumber;
									           $propertylocation->SubdivisionNumSearch = $listing['SubdivisionNumSearch'];
									           $propertylocation->TaxDistrict = $listing['TaxDistrict']; 
									           
								    	       $propertylocation->save();
					                        }

 			  


  			$key++;
			}


			        foreach ($search_result as $add=>$address) {
				        $formattedAddr = str_replace(' ','+',$address['PublicAddress']);
				        $final_address=$formattedAddr.'+'.$address['PostalCode'];
				        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$final_address.'&key=AIzaSyBikaraM6wOzK1NN0fqhPOeiJeqvuddfGQ'); 
				        $output = json_decode($geocodeFromAddr);
				       ;

				       $data[$add]['formatted_address'] = $data[$add]['latitude'] = $data[$add]['longitude'] = '';
                       if(isset($output->results[0]->geometry->location->lat) && $output->results[0]->geometry->location->lat != '')
                       {
                       		 $data[$add]['latitude']  = $output->results[0]->geometry->location->lat; 
                       }

                       if(isset($output->results[0]->geometry->location->lng) && $output->results[0]->geometry->location->lng!= '')
                       {
				        
				        $data[$add]['longitude'] = $output->results[0]->geometry->location->lng;

                       }

                       if(isset($output->results[0]->formatted_address) && $output->results[0]->formatted_address
                       	!='')
                       {
                       		$data[$add]['formatted_address'] = $output->results[0]->formatted_address;
                       }
				       
				        
       					 //Return latitude and longitude of the given address
				       
				        $is_property_latlong = PropertyLatLong::where('Matrix_Unique_ID', '=', $address['Matrix_Unique_ID'])->first();
				        if($is_property_latlong)
				        {
				        	 
					        $is_property_latlong->MLSNumber = $address['MLSNumber'];
					        $is_property_latlong->latitude = $data[$add]['latitude'];
					        $is_property_latlong->longitude = $data[$add]['longitude'];
					        $is_property_latlong->save();
				        }
				        else
				        {
				        	
				        	$latlong = new PropertyLatLong();
					        $latlong->Matrix_Unique_ID = $address['Matrix_Unique_ID'];
					        $latlong->MLSNumber = $address['MLSNumber'];
					        $latlong->latitude = $data[$add]['latitude'];
					        $latlong->longitude = $data[$add]['longitude'];
					        $latlong->save();
				       }

				        

    				}


			   
		  }
		  else 
		  {
			        echo "  + Not connected:<br>\n";
			        echo "<pre>";
			        print_r($rets->Error());
			        exit;
		 }

		 return view('rets.search', ['data'=>$data,'search_result'=>$search_result, 'total_records'=>$result_count]);
}
}