<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateProperty;
use App\PropertyDetail;
use Illuminate\Http\Request;

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
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])
                    ->paginate($perPage);
            } elseif ($request->has('postal_code')) {
                $property = PropertyDetail::where('PostalCode', $request->postal_code)
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])
                    ->paginate($perPage);
            } elseif ($request->has('address')) {
                $property = PropertyDetail::where('PublicAddress', $request->address)
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])
                    ->paginate($perPage);
            } elseif ($request->has('search_community')) {
                $search_community = $request->search_community;
                $property = PropertyDetail::WhereHas('propertylocation', function ($query) use ($search_community) {
                    $query->where('CommunityName', $search_community);
                })
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])
                    ->paginate($perPage);
            } elseif ($request->has('listing_id')) {
                $property = PropertyDetail::where('MLSNumber', $request->listing_id)
                    ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])
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

    public function viewMore($matrix_unique_id)
    {
        try {
            $PropertyLocation = PropertyDetail::where('Matrix_Unique_ID', '=', $matrix_unique_id)
                ->with(['propertyfeature', 'propertyadditional', 'propertyexternalfeature', 'propertyimage', 'propertyfinancialdetail', 'propertyinteriorfeature', 'propertyinteriorfeature', 'propertylatlong', 'propertylocation'])
                ->first();
            if (count($PropertyLocation->propertyimage) < 3) {
                $imagejob = (new UpdateProperty($matrix_unique_id));
                $this->dispatch($imagejob);
                return response()->json([
                    'success' => true,
                    'message' => 'Live Images',
                    'results' => $PropertyLocation,
                    'hasImage' => false
                ],200);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Database Images',
                    'results' => $PropertyLocation
                ],200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],500);
        }
    }
}
