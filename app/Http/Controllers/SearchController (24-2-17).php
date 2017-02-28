<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phRETS;
use \DB;
use App\PropertyDetail;
use App\PropertyImage;


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
	  
    	$rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
		$rets_username = "neal";
		$rets_password = "glvar";

		$rets = new phRETS;

		$rets->AddHeader("RETS-Version", "RETS/1.7.2");

		$connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);

		if ($connect) 
		{
		        echo "  + Connected";
		       
		        if($city!=""){
				$query="(City={$city})";
    			}



		        $results = $rets->SearchQuery(
			    "Property",
			    "Listing",
			    $query);
			   
		        $result_count=$rets->TotalRecordsFound();
		        echo $result_count;
		        // $listing = $rets->FetchRow($results);
		        // dd($listing);

					for($i=0 ; $i< $result_count; $i++ )
					{
						$listing = $rets->FetchRow($results);
						//echo $listing['Matrix_Unique_ID'].'/'.$listing['MLSNumber'].'/'.$listing['City']."<br />\n";
						// echo '<pre>';
						// 	print_r($listing);
						// echo '</pre>';





					              if($listing['BathsHalf']== '') 
					              {
					              	$listing['BathsHalf'] =0;
					              }

					              if(isset($listing['BathsFull']) && $listing['BathsFull']!= '') 
					              {
					              	$BathsFull = $listing['BathsFull'];
					              }
					              else
					              {
					              		$BathsFull = 0;
					              }

					              if(isset($listing['BedroomsTotalPossibleNum']) && $listing['BedroomsTotalPossibleNum']!= '') 
					              {
					              	$BedroomsTotalPossibleNum = $listing['BedroomsTotalPossibleNum'] =0;
					              }
					              else
					              {
					              		$BedroomsTotalPossibleNum = 0;
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

					              if(isset($listing['City']) && $listing['City'] !='')
					              {
					               			

					               			// $is_property = PropertyDetail::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();


							                //  if ($is_property) 
							                //  {
											  

								    	 	   // $is_property->Matrix_Unique_ID 		= $listing['Matrix_Unique_ID'];
									         //   $is_property->ListPrice 		        = $listing['ListPrice'];
									         //   $is_property->Status 		        = $listing['Status'];
									         //   $is_property->BedroomsTotalPossibleNum = $listing['BedroomsTotalPossibleNum']; 
									         //   $is_property->BathsTotal= $listing['BathsTotal']; 
									         //   $is_property->BathsHalf = $listing['BathsHalf']; 
									         //   $is_property->BathsFull = $listing['BathsFull']; 
									         //   $is_property->NumAcres  = $listing['NumAcres']; 
									         //   $is_property->SqFtTotal = $listing['SqFtTotal']; 
									         //   $is_property->StreetNumber = $listing['StreetNumber']; 
									         //   $is_property->StreetName   = $listing['StreetName']; 
									         //   $is_property->City         = $listing['City'];
									         //   $is_property->MLSNumber    = $listing['MLSNumber'];
								    	     //   $is_property->save();





										 //    }
											// else
											// {



												$values[]= array('Matrix_Unique_ID' => $listing['Matrix_Unique_ID'], 
														           'ListPrice' 		  => $listing['ListPrice'], 
														           'Status' 		  => $listing['Status'], 
														           'BedroomsTotalPossibleNum' => $BedroomsTotalPossibleNum, 
														           'BathsTotal'=> $BathsTotal, 
														           'BathsHalf' => $listing['BathsHalf'], 
														           'BathsFull' => $BathsFull, 
														           'NumAcres'  => $NumAcres, 
														           'SqFtTotal' => $SqFtTotal, 
														           'StreetNumber' => $listing['StreetNumber'], 
														           'StreetName'   => $listing['StreetName'], 
														           'City'         => $listing['City'], 
														           'MLSNumber'    => $listing['MLSNumber']
														          );


											   // $property =new PropertyDetail();

								    	 // 	   $property->Matrix_Unique_ID 		= $listing['Matrix_Unique_ID'];
									     //       $property->ListPrice 		    = $listing['ListPrice'];
									     //       $property->Status 		        = $listing['Status'];
									     //       $property->BedroomsTotalPossibleNum = $listing['BedroomsTotalPossibleNum']; 
									     //       $property->BathsTotal= $listing['BathsTotal']; 
									     //       $property->BathsHalf = $listing['BathsHalf']; 
									     //       $property->BathsFull = $listing['BathsFull']; 
									     //       $property->NumAcres  = $listing['NumAcres']; 
									     //       $property->SqFtTotal = $listing['SqFtTotal']; 
									     //       $property->StreetNumber = $listing['StreetNumber']; 
									     //       $property->StreetName   = $listing['StreetName']; 
									     //       $property->City         = $listing['City'];
									     //       $property->MLSNumber    = $listing['MLSNumber'];
								    	 //       $property->save();

										$photos = $rets->GetObject("Property", "Photo", $listing['Matrix_Unique_ID'], "*", 0);
										echo count($photos);

											foreach ($photos as $photo) {
											        //$ContentId   = $photo['Content-ID'];
											        //$ObjectId    = $photo['Object-ID'];
											  		//$Success = $photo['Success'];
											        //$ContentType = $photo['Content-Type'];
											        //$encoded_image = base64_encode($photo['Data']); 
												    //$ContentDesc = $photo['Content-Description']; 


											$images[]= array(
												           'ContentId'   => $photo['Content-ID'], 
												           'ObjectId'    => $photo['Object-ID'], 
												           'Success'     => $photo['Success'], 
												           'ContentType' => $photo['Content-Type'], 
												           'Encoded_image'   => $encoded_image,
												           'ContentDesc' => $photo['Content-Description']
												          );


											     
											    //}

										}


					              //}

					               

					              


					}
				}


      //                $property = PropertyDetail::insert($values);

					// if($property)
					// {
					// 	echo 'done';
					// }

				$imagesData = PropertyImage::insert($images);

				    if($imagesData)
					{
						echo 'done';
					}
							        

			}
			else
			{
			        echo "  + Not connected";
			        // echo "<pre>";
			        // print_r($rets->Error());
			        // exit;
			}


  }
}
