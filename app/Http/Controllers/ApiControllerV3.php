<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\InsertImages;
use App\Jobs\UpdateProperty;
use App\PropertyDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ApiControllerV3 extends Controller
{
  public function viewMore($MLSNumber)
  {
      try {
          $PropertyLocation = PropertyDetail::where('MLSNumber',$MLSNumber)
              ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])
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
              ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])
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
}
