<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\InsertImages;
use App\Jobs\UpdateProperty;
use App\PropertyDetail;
use App\PropertyImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use phRETS;

class ApiControllerV3 extends Controller
{
    /*
     * Setting executoin time and memory_limit during intialization of class
     */
    
    public function __construct() {
        ini_set('max_execution_time', 30000000);
        set_time_limit(0);
        ini_set('memory_limit', '256M');

    }
    
  public function viewMore($MLSNumber)
  {
      try {
          $PropertyLocation = PropertyDetail::where('MLSNumber',$MLSNumber)
              ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                  'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                  'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                  'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                  'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                  'PropertyInsurance','PropertySellingDetails'])
              ->first();
          if (count($PropertyLocation->propertyimage) < 3) {
              $this->updateGalary($PropertyLocation->Matrix_Unique_ID);
              return response()->json([
                  'success' => true,
                  'message' => 'Live Images',
                  'results' => $PropertyLocation,
                  'hasImage' => false
              ],200);
          } else {
              $this->thresholdCheck($PropertyLocation->Matrix_Unique_ID);
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
  public function photoGallery($MLSNumber)
  {
      try{
          $property = PropertyDetail::where('MLSNumber',$MLSNumber)
              ->with('propertyimage')->first();
          if (count($property->propertyimage) < 3) {
              $this->updateGalary($property->Matrix_Unique_ID);
              return response()->json([
                  'success' => true,
                  'message' => 'Live Images',
                  'results' => $property,
                  'hasImage' => false
              ],200);
          } else {
              $this->thresholdCheck($property->Matrix_Unique_ID);
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
  public function mortgageCalculator($MLSNumber)
  {
      try{
          $propertyMortgage = PropertyDetail::where('MLSNumber',$MLSNumber)
              ->with('propertyimage')
              ->first();
          $this->thresholdCheck($propertyMortgage->Matrix_Unique_ID);
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
  public function printableFlyer($MLSNumber)
  {
      try{
          $propertyPrintable = PropertyDetail::where('MLSNumber',$MLSNumber)
              ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature',
                  'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature',
                  'propertyinteriorfeature', 'propertylatlong', 'propertylocation',
                  'PropertyMiscellaneous','PropertyAdditionalFeature','PropertyAdditionalDetail',
                  'PropertyInteriorDetail','PropertyFinancialAdditional','PropertyOtherInformation',
                  'PropertyInsurance','PropertySellingDetails'])
              ->first();
          $this->thresholdCheck($propertyPrintable->Matrix_Unique_ID);
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
    
    /*
     * @function : propertyImagesByListingID
     * @params : listing_id
     * @Return : Array || Boolean | Return all images based on listing id
     */
    public function propertyImagesByListingID($listing_id){
        try {
            $rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
            $rets_username = "neal";
            $rets_password = "glvar";
            $rets = new phRETS;
            $rets->AddHeader("RETS-Version", "RETS/1.7.2");
            $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
            if ($connect) {
                Log::info('connect');
                $listing = PropertyDetail::where('MLSNumber',$listing_id)->first();
                $photos = $rets->GetObject("Property", "LargePhoto", $listing->Matrix_Unique_ID, "*", 0);
                $deleteImage = PropertyImage::where('Matrix_Unique_ID', '=', $listing->MLSNumber)->delete();
                $contentType = $property_image = '';
                $content_id = $object_id = $Success = 0;
                foreach ($photos as $photo) {
                    if (isset($photo['Content-ID']) && $photo['Content-ID'] != '') {
                        $content_id = $photo['Content-ID'];
                    }
                    if (isset($photo['Object-ID']) && $photo['Object-ID'] != '') {
                        $object_id = $photo['Object-ID'];
                    }
                    if (isset($photo['Success']) && $photo['Success'] != '') {
                        $Success = $photo['Success'];
                    }
                    if ($photo['Success'] == true && isset($photo['Content-Type']) && $photo['Content-Type'] != '') {
                        $contentType = $photo['Content-Type'];
                        $property_image = base64_encode($photo['Data']);
                    } else {
                        $contentType = '';
                        $property_image = '';
                    }
                    if (isset($photo['Content-Description']) && $photo['Content-Description'] != '') {
                        $ContentDescription = $photo['Content-Description'];
                    } else {
                        $ContentDescription = '';
                    }
                    $propertyimage = new PropertyImage();
                    $propertyimage->Matrix_Unique_ID = $listing->Matrix_Unique_ID;
                    $propertyimage->MLSNumber = $listing->MLSNumber;
                    $propertyimage->ContentId = $content_id;
                    $propertyimage->ObjectId = $object_id;
                    $propertyimage->Success = $Success;
                    $propertyimage->ContentType = $contentType;
                    $propertyimage->Encoded_image = $property_image;
                    $propertyimage->ContentDesc = $ContentDescription;
                    $propertyimage->save();
                }
                return response()->json([
                    'success' => TRUE,
                    'message' => 'Images are successfully imported'
                ], 200);
            }
        } catch (\Exception $ex){
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ], 500);
        }
    }
}
