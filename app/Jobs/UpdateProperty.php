<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
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

class UpdateProperty implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $Matrix_Unique_ID;

    public function __construct($Matrix_Unique_ID)
    {
        $this->Matrix_Unique_ID = $Matrix_Unique_ID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('queue Start');
        try {
            $Matrix_Unique_ID = $this->Matrix_Unique_ID;
            $rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
            $rets_username = "neal";
            $rets_password = "glvar";
            $rets = new \phRETS();
            $rets->AddHeader("RETS-Version", "RETS/1.7.2");
            $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
            if ($connect) {
                Log::info('connect');
                $query = "(Matrix_Unique_ID={$Matrix_Unique_ID})";
                $search = $rets->Search("Property", "Listing", $query, array("StandardNames" => 0));
                $listing = $search[0];
                Log::info('Get Result from Rets');
                if (isset($listing['BathsHalf']) && $listing['BathsHalf'] != '') {
                    $BathsHalf = $listing['BathsHalf'];
                } else {
                    $BathsHalf = 0;
                }

                if (isset($listing['BathsFull']) && $listing['BathsFull'] != '') {
                    $BathsFull = $listing['BathsFull'];
                } else {
                    $BathsFull = 0;
                }


                if (isset($listing['SqFtTotal']) && $listing['SqFtTotal'] != '') {
                    $SqFtTotal = $listing['SqFtTotal'];
                } else {
                    $SqFtTotal = 0;
                }

                if (isset($listing['BathsTotal']) && $listing['BathsTotal'] != '') {
                    $BathsTotal = $listing['BathsTotal'];
                } else {
                    $BathsTotal = 0;
                }


                if (isset($listing['NumAcres']) && $listing['NumAcres'] != '') {
                    $NumAcres = $listing['NumAcres'];
                } else {
                    $NumAcres = 0;
                }

                if (isset($listing['YearBuilt']) && $listing['YearBuilt'] != '') {
                    $YearBuilt = $listing['YearBuilt'];
                } else {
                    $YearBuilt = 0;
                }

                if (isset($listing['Garage']) && $listing['Garage'] != '') {
                    $Garage = $listing['Garage'];
                } else {
                    $Garage = 0;
                }

                if (isset($listing['LotSqft']) && $listing['LotSqft'] != '') {
                    $LotSqft = $listing['LotSqft'];
                } else {
                    $LotSqft = 0;
                }


                if (isset($listing['Assessments']) && $listing['Assessments'] != '') {
                    $Assessments = $listing['Assessments'];
                } else {
                    $Assessments = 0;
                }

                if (isset($listing['YearRoundSchoolYN']) && $listing['YearRoundSchoolYN'] != '') {
                    $YearRoundSchoolYN = $listing['YearRoundSchoolYN'];
                } else {
                    $YearRoundSchoolYN = 0;
                }

                if (isset($listing['RefrigeratorYN']) && $listing['RefrigeratorYN'] != '') {
                    $RefrigeratorYN = $listing['RefrigeratorYN'];
                } else {
                    $RefrigeratorYN = 0;
                }

                if (isset($listing['RealtorYN']) && $listing['RealtorYN'] != '') {
                    $RealtorYN = $listing['RealtorYN'];
                } else {
                    $RealtorYN = 0;
                }

                if (isset($listing['GreenBuildingCertificationYN']) && $listing['GreenBuildingCertificationYN'] != '') {
                    $GreenBuildingCertificationYN = $listing['GreenBuildingCertificationYN'];
                } else {
                    $GreenBuildingCertificationYN = 0;
                }

                if (isset($listing['GatedYN']) && $listing['GatedYN'] != '') {
                    $GatedYN = $listing['GatedYN'];
                } else {
                    $GatedYN = 0;
                }

                if (isset($listing['CourtApproval']) && $listing['CourtApproval'] != '') {
                    $CourtApproval = $listing['CourtApproval'];
                } else {
                    $CourtApproval = 0;
                }

                // ------------------------------------------

                if (isset($listing['AnnualPropertyTaxes']) && $listing['AnnualPropertyTaxes'] != '') {
                    $AnnualPropertyTaxes = $listing['AnnualPropertyTaxes'];
                } else {
                    $AnnualPropertyTaxes = 0;
                }

                if (isset($listing['AppxAssociationFee']) && $listing['AppxAssociationFee'] != '') {
                    $AppxAssociationFee = $listing['AppxAssociationFee'];
                } else {
                    $AppxAssociationFee = 0;
                }

                if (isset($listing['AssociationFee1']) && $listing['AssociationFee1'] != '') {
                    $AssociationFee1 = $listing['AssociationFee1'];
                } else {
                    $AssociationFee1 = 0;
                }

                if (isset($listing['AVMYN']) && $listing['AVMYN'] != '') {
                    $AVMYN = $listing['AVMYN'];
                } else {
                    $AVMYN = 0;
                }

                if (isset($listing['ForeclosureCommencedYN']) && $listing['ForeclosureCommencedYN'] != '') {
                    $ForeclosureCommencedYN = $listing['ForeclosureCommencedYN'];
                } else {
                    $ForeclosureCommencedYN = 0;
                }

                if (isset($listing['EarnestDeposit']) && $listing['EarnestDeposit'] != '') {
                    $EarnestDeposit = $listing['EarnestDeposit'];
                } else {
                    $EarnestDeposit = 0;
                }


                if (isset($listing['MasterPlanFeeAmount']) && $listing['MasterPlanFeeAmount'] != '') {
                    $MasterPlanFeeAmount = $listing['MasterPlanFeeAmount'];
                } else {
                    $MasterPlanFeeAmount = 0;
                }

                if (isset($listing['RepoReoYN']) && $listing['RepoReoYN'] != '') {
                    $RepoReoYN = $listing['RepoReoYN'];
                } else {
                    $RepoReoYN = 0;
                }

                if (isset($listing['ShortSale']) && $listing['ShortSale'] != '') {
                    $ShortSale = $listing['ShortSale'];
                } else {
                    $ShortSale = 0;
                }

                if (isset($listing['SIDLIDYN']) && $listing['SIDLIDYN'] != '') {
                    $SIDLIDYN = $listing['SIDLIDYN'];
                } else {
                    $SIDLIDYN = 0;
                }

                //------------------------------------------

                if (isset($listing['ApproxTotalLivArea']) && $listing['ApproxTotalLivArea'] != '') {
                    $ApproxTotalLivArea = $listing['ApproxTotalLivArea'];
                } else {
                    $ApproxTotalLivArea = 0;
                }

                if (isset($listing['BathDownYN']) && $listing['BathDownYN'] != '') {
                    $BathDownYN = $listing['BathDownYN'];
                } else {
                    $BathDownYN = 0;
                }

                if (isset($listing['BedroomDownstairsYN']) && $listing['BedroomDownstairsYN'] != '') {
                    $BedroomDownstairsYN = $listing['BedroomDownstairsYN'];
                } else {
                    $BedroomDownstairsYN = 0;
                }

                if (isset($listing['BedroomsTotalPossibleNum']) && $listing['BedroomsTotalPossibleNum'] != '') {
                    $BedroomsTotalPossibleNum = $listing['BedroomsTotalPossibleNum'];
                } else {
                    $BedroomsTotalPossibleNum = 0;
                }

                if (isset($listing['DishwasherYN']) && $listing['DishwasherYN'] != '') {
                    $DishwasherYN = $listing['DishwasherYN'];
                } else {
                    $DishwasherYN = 0;
                }

                if (isset($listing['DisposalYN']) && $listing['DisposalYN'] != '') {
                    $DisposalYN = $listing['DisposalYN'];
                } else {
                    $DisposalYN = 0;
                }

                if (isset($listing['DryerIncluded']) && $listing['DryerIncluded'] != '') {
                    $DryerIncluded = $listing['DryerIncluded'];
                } else {
                    $DryerIncluded = 0;
                }

                if (isset($listing['Fireplaces']) && $listing['Fireplaces'] != '') {
                    $Fireplaces = $listing['Fireplaces'];
                } else {
                    $Fireplaces = 0;
                }

                if (isset($listing['NumDenOther']) && $listing['NumDenOther'] != '') {
                    $NumDenOther = $listing['NumDenOther'];
                } else {
                    $NumDenOther = 0;
                }

                if (isset($listing['RoomCount']) && $listing['RoomCount'] != '') {
                    $RoomCount = $listing['RoomCount'];
                } else {
                    $RoomCount = 0;
                }

                if (isset($listing['ThreeQtrBaths']) && $listing['ThreeQtrBaths'] != '') {
                    $ThreeQtrBaths = $listing['ThreeQtrBaths'];
                } else {
                    $ThreeQtrBaths = 0;
                }

                if (isset($listing['WasherIncluded']) && $listing['WasherIncluded'] != '') {
                    $WasherIncluded = $listing['WasherIncluded'];
                } else {
                    $WasherIncluded = 0;
                }

                //-------------------------------------

                if (isset($listing['StreetNumberNumeric']) && $listing['StreetNumberNumeric'] != '') {
                    $StreetNumberNumeric = $listing['StreetNumberNumeric'];
                } else {
                    $StreetNumberNumeric = 0;
                }

                if (isset($listing['SubdivisionNumber']) && $listing['SubdivisionNumber'] != '') {
                    $SubdivisionNumber = $listing['SubdivisionNumber'];
                } else {
                    $SubdivisionNumber = 0;
                }


                if (isset($listing['ConvertedGarageYN']) && $listing['ConvertedGarageYN'] != '') {
                    $ConvertedGarageYN = $listing['ConvertedGarageYN'];
                } else {
                    $ConvertedGarageYN = 0;
                }


                if (isset($listing['PvPool']) && $listing['PvPool'] != '') {
                    $PvPool = $listing['PvPool'];
                } else {
                    $PvPool = 0;
                }


                if (isset($listing['AgeRestrictedCommunityYN']) && $listing['AgeRestrictedCommunityYN'] != '') {
                    $AgeRestrictedCommunityYN = $listing['AgeRestrictedCommunityYN'];
                } else {
                    $AgeRestrictedCommunityYN = 0;
                }

                if (isset($listing['RATIO_CurrentPrice_By_SQFT']) && $listing['RATIO_CurrentPrice_By_SQFT'] != '') {
                    $RATIO_CurrentPrice_By_SQFT = $listing['RATIO_CurrentPrice_By_SQFT'];
                } else {
                    $RATIO_CurrentPrice_By_SQFT = 0;
                }

                if (isset($listing['CurrentPrice']) && $listing['CurrentPrice'] != '') {
                    $CurrentPrice = $listing['CurrentPrice'];
                } else {
                    $CurrentPrice = 0;
                }
                //Update Project Details Table
                $newPropertyDetails = PropertyDetail::where('Matrix_Unique_ID', $Matrix_Unique_ID)->first();
                Log::info('Property Details');
                if ($newPropertyDetails != null) {
                    //Update Lat Long
                    if ($newPropertyDetails->PublicAddress != $listing['PublicAddress']) {
                        try{
                            $formattedAddr = str_replace(' ', '+', $listing['PublicAddress']);
                            $final_address = $formattedAddr . '+' . $listing['PostalCode'];
                            $client = new Client();
                            $geocodeFromAddr = $client->request('GET','https://maps.googleapis.com/maps/api/geocode/json?address=' . $final_address . '&key='.env('GOOGLEAPIKEY'));
                            if($geocodeFromAddr->getStatusCode() == 200){
                                $output = json_decode($geocodeFromAddr->getBody());
                                $data['formatted_address'] = $data['latitude'] = $data['longitude'] = '';
                                if (isset($output->results[0]->geometry->location->lat) && $output->results[0]->geometry->location->lat != '') {
                                    $data['latitude'] = $output->results[0]->geometry->location->lat;
                                }

                                if (isset($output->results[0]->geometry->location->lng) && $output->results[0]->geometry->location->lng != '') {

                                    $data['longitude'] = $output->results[0]->geometry->location->lng;

                                }

                                if (isset($output->results[0]->formatted_address) && $output->results[0]->formatted_address
                                    != ''
                                ) {
                                    $data['formatted_address'] = $output->results[0]->formatted_address;
                                }
                                $latLong = PropertyLatLong::where('Matrix_Unique_ID', $Matrix_Unique_ID)->first();
                                if($latLong != null){
                                    $latLong->MLSNumber = $listing['MLSNumber'];
                                    $latLong->latitude = $data['latitude'];
                                    $latLong->longitude = $data['longitude'];
                                    $latLong->FormatedAddress = $data['formatted_address'];
                                    $latLong->save();
                                } else {
                                    $latlong = new PropertyLatLong();
                                    $latlong->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                    $latlong->MLSNumber = $listing['MLSNumber'];
                                    $latlong->latitude = $data['latitude'];
                                    $latlong->longitude = $data['longitude'];
                                    $latlong->FormatedAddress = $data['formatted_address'];
                                    $latlong->save();
                                }
                            }
                        } catch (\Exception $e){
                            Log::info('google map error !! '.$e->getMessage());
                        }
                    }
                    $newPropertyDetails->ListPrice = $listing['ListPrice'];
                    $newPropertyDetails->Status = $listing['Status'];
                    $newPropertyDetails->BedroomsTotalPossibleNum = $BedroomsTotalPossibleNum;
                    $newPropertyDetails->BathsTotal = $BathsTotal;
                    $newPropertyDetails->BathsHalf = $BathsHalf;
                    $newPropertyDetails->BathsFull = $BathsFull;
                    $newPropertyDetails->NumAcres = $NumAcres;
                    $newPropertyDetails->SqFtTotal = $SqFtTotal;
                    $newPropertyDetails->StreetNumber = $listing['StreetNumber'];
                    $newPropertyDetails->StreetName = $listing['StreetName'];
                    $newPropertyDetails->City = $listing['City'];
                    $newPropertyDetails->MLSNumber = $listing['MLSNumber'];
                    $newPropertyDetails->PostalCode = $listing['PostalCode'];
                    $newPropertyDetails->PhotoCount = $listing['PhotoCount'];
                    $newPropertyDetails->PublicAddress = $listing['PublicAddress'];
                    $newPropertyDetails->VirtualTourLink = $listing['VirtualTourLink'];
                    $newPropertyDetails->update();
                } else {
                    $newPropertyDetails = new PropertyDetail();
                    $newPropertyDetails->ListPrice = $listing['ListPrice'];
                    $newPropertyDetails->Status = $listing['Status'];
                    $newPropertyDetails->BedroomsTotalPossibleNum = $BedroomsTotalPossibleNum;
                    $newPropertyDetails->BathsTotal = $BathsTotal;
                    $newPropertyDetails->BathsHalf = $BathsHalf;
                    $newPropertyDetails->BathsFull = $BathsFull;
                    $newPropertyDetails->NumAcres = $NumAcres;
                    $newPropertyDetails->SqFtTotal = $SqFtTotal;
                    $newPropertyDetails->StreetNumber = $listing['StreetNumber'];
                    $newPropertyDetails->StreetName = $listing['StreetName'];
                    $newPropertyDetails->City = $listing['City'];
                    $newPropertyDetails->MLSNumber = $listing['MLSNumber'];
                    $newPropertyDetails->PostalCode = $listing['PostalCode'];
                    $newPropertyDetails->PhotoCount = $listing['PhotoCount'];
                    $newPropertyDetails->PublicAddress = $listing['PublicAddress'];
                    $newPropertyDetails->VirtualTourLink = $listing['VirtualTourLink'];
                    $newPropertyDetails->save();

                    //Update Lat Long
                    try{
                        $formattedAddr = str_replace(' ', '+', $listing['PublicAddress']);
                        $final_address = $formattedAddr . '+' . $listing['PostalCode'];
                        $client = new Client();
                        $geocodeFromAddr = $client->request('GET','https://maps.googleapis.com/maps/api/geocode/json?address=' . $final_address . '&key=AIzaSyCnzJ15XOMd1ntur0iXSq6VqeM4wAwkCrE');
                        if($geocodeFromAddr->getStatusCode() == 200){
                            $output = json_decode($geocodeFromAddr->getBody());
                            $data['formatted_address'] = $data['latitude'] = $data['longitude'] = '';
                            if (isset($output->results[0]->geometry->location->lat) && $output->results[0]->geometry->location->lat != '') {
                                $data['latitude'] = $output->results[0]->geometry->location->lat;
                            }

                            if (isset($output->results[0]->geometry->location->lng) && $output->results[0]->geometry->location->lng != '') {

                                $data['longitude'] = $output->results[0]->geometry->location->lng;

                            }

                            if (isset($output->results[0]->formatted_address) && $output->results[0]->formatted_address
                                != ''
                            ) {
                                $data['formatted_address'] = $output->results[0]->formatted_address;
                            }
                            $latLong = PropertyLatLong::where('Matrix_Unique_ID', $Matrix_Unique_ID)->first();
                            if($latLong != null){
                                $latLong->MLSNumber = $listing['MLSNumber'];
                                $latLong->latitude = $data['latitude'];
                                $latLong->longitude = $data['longitude'];
                                $latLong->FormatedAddress = $data['formatted_address'];
                                $latLong->save();
                            } else {
                                $latlong = new PropertyLatLong();
                                $latlong->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                $latlong->MLSNumber = $listing['MLSNumber'];
                                $latlong->latitude = $data['latitude'];
                                $latlong->longitude = $data['longitude'];
                                $latlong->FormatedAddress = $data['formatted_address'];
                                $latlong->save();
                            }
                        }
                    } catch (\Exception $e){
                        Log::info('google map error !! '.$e->getMessage());
                    }
                }
                //Update Property Additional
                Log::info('Property Additional');
                $is_property_additional = PropertyAdditional::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();
                if ($is_property_additional) {
                    $is_property_additional->MLSNumber = $listing['MLSNumber'];
                    $is_property_additional->AgeRestrictedCommunityYN = $AgeRestrictedCommunityYN;
                    $is_property_additional->Assessments = $Assessments;
                    $is_property_additional->AssociationFeaturesAvailable = $listing['AssociationFeaturesAvailable'];
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
                    $is_property_additional->update();
                } else {
                    $propertyadditional = new PropertyAdditional();
                    $propertyadditional->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                    $propertyadditional->MLSNumber = $listing['MLSNumber'];
                    $propertyadditional->AgeRestrictedCommunityYN = $AgeRestrictedCommunityYN;
                    $propertyadditional->Assessments = $Assessments;
                    $propertyadditional->AssociationFeaturesAvailable = $listing['AssociationFeaturesAvailable'];
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
                    $propertyadditional->ListOfficeName = $listing['ListOfficeName'];
                    $propertyadditional->ListAgentDirectWorkPhone = $listing['ListAgentDirectWorkPhone'];
                    $propertyadditional->ListOfficeName = $listing['ListOfficeName'];
                    $propertyadditional->ListAgentDirectWorkPhone = $listing['ListAgentDirectWorkPhone'];
                    $propertyadditional->RealtorYN = $RealtorYN;
                    $propertyadditional->RefrigeratorYN = $RefrigeratorYN;
                    $propertyadditional->Spa = $listing['Spa'];
                    $propertyadditional->SpaDescription = $listing['SpaDescription'];
                    $propertyadditional->YearRoundSchoolYN = $YearRoundSchoolYN;
                    $propertyadditional->save();
                }
                Log::info('Property External');
                //Update Property External feature
                $is_property_external_feature = PropertyExternalFeature::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();
                if ($is_property_external_feature) {
                    $is_property_external_feature->MLSNumber = $listing['MLSNumber'];
                    $is_property_external_feature->BuildingDescription = $listing['BuildingDescription'];
                    $is_property_external_feature->BuiltDescription = $listing['BuiltDescription'];
                    $is_property_external_feature->ConstructionDescription = $listing['ConstructionDescription'];
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
                    $is_property_external_feature->update();
                } else {
                    $propertyexternalfeature = new PropertyExternalFeature();
                    $propertyexternalfeature->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                    $propertyexternalfeature->MLSNumber = $listing['MLSNumber'];
                    $propertyexternalfeature->BuildingDescription = $listing['BuildingDescription'];
                    $propertyexternalfeature->BuiltDescription = $listing['BuiltDescription'];
                    $propertyexternalfeature->ConstructionDescription = $listing['ConstructionDescription'];
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
                Log::info('Property Feature');
                //Update Property Feature
                $is_property_feature = PropertyFeature::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();
                if ($is_property_feature) {
                    $is_property_feature->YearBuilt = $YearBuilt;
                    $is_property_feature->PropertyType = $listing['PropertyType'];
                    $is_property_feature->PropertySubType = $listing['PropertySubType'];
                    $is_property_feature->CountyOrParish = $listing['CountyOrParish'];
                    $is_property_feature->Zoning = $listing['Zoning'];
                    $is_property_feature->MLSNumber = $listing['MLSNumber'];
                    $is_property_feature->update();
                } else {
                    $propertyfeature = new PropertyFeature();
                    $propertyfeature->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                    $propertyfeature->YearBuilt = $YearBuilt;
                    $propertyfeature->PropertyType = $listing['PropertyType'];
                    $propertyfeature->PropertySubType = $listing['PropertySubType'];
                    $propertyfeature->CountyOrParish = $listing['CountyOrParish'];
                    $propertyfeature->Zoning = $listing['Zoning'];
                    $propertyfeature->MLSNumber = $listing['MLSNumber'];
                    $propertyfeature->save();
                }
                Log::info('property_financial_details');
                //Update property_financial_details
                $is_property_financial_detail = PropertyFinancialDetail::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();
                if ($is_property_financial_detail) {
                    $is_property_financial_detail->MLSNumber = $listing['MLSNumber'];
                    $is_property_financial_detail->AnnualPropertyTaxes = $AnnualPropertyTaxes;
                    $is_property_financial_detail->AppxAssociationFee = $AppxAssociationFee;
                    $is_property_financial_detail->AssociationFee1 = $AssociationFee1;
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
                    $is_property_financial_detail->update();
                } else {
                    $propertyfinancialdetail = new PropertyFinancialDetail();
                    $propertyfinancialdetail->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                    $propertyfinancialdetail->MLSNumber = $listing['MLSNumber'];
                    $propertyfinancialdetail->AnnualPropertyTaxes = $AnnualPropertyTaxes;
                    $propertyfinancialdetail->AppxAssociationFee = $AppxAssociationFee;
                    $propertyfinancialdetail->AssociationFee1 = $AssociationFee1;
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
                Log::info('Update Images');
                //Update Images
                $photos = $rets->GetObject("Property", "LargePhoto", $listing['Matrix_Unique_ID'], "*", 0);
                $deleteImage = PropertyImage::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->delete();
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
                        $property_image = '';
                    }
                    if (isset($listing['Content-Description']) && $listing['Content-Description'] != '') {
                        $ContentDescription = $listing['Content-Description'];
                    } else {
                        $ContentDescription = '';
                    }
                    $propertyimage = new PropertyImage();
                    $propertyimage->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                    $propertyimage->MLSNumber = $listing['MLSNumber'];
                    $propertyimage->ContentId = $content_id;
                    $propertyimage->ObjectId = $object_id;
                    $propertyimage->Success = $Success;
                    $propertyimage->ContentType = $contentType;
                    $propertyimage->Encoded_image = $property_image;
                    $propertyimage->ContentDesc = $ContentDescription;
                    $propertyimage->save();
                }
                Log::info('property_interior_features');
                //Update property_interior_features
                $is_property_interior_feature = PropertyInteriorFeature::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();
                if ($is_property_interior_feature) {
                    $is_property_interior_feature->MLSNumber = $listing['MLSNumber'];
                    $is_property_financial_detail->ApproxTotalLivArea = $ApproxTotalLivArea;
                    $is_property_financial_detail->BathDownstairsDescription = $listing['BathDownstairsDescription'];
                    $is_property_financial_detail->BathDownYN = $BathDownYN;
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
                    $is_property_interior_feature->update();
                } else {
                    $propertyfinancialdetail = new PropertyInteriorFeature();
                    $propertyfinancialdetail->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                    $propertyfinancialdetail->MLSNumber = $listing['MLSNumber'];
                    $propertyfinancialdetail->ApproxTotalLivArea = $ApproxTotalLivArea;
                    $propertyfinancialdetail->BathDownstairsDescription = $listing['BathDownstairsDescription'];
                    $propertyfinancialdetail->BathDownYN = $BathDownYN;
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
                Log::info('Property Location');
                //Update Property Location
                $is_property_location = PropertyLocation::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();
                if ($is_property_location) {
                    $is_property_location->MLSNumber = $listing['MLSNumber'];
                    $is_property_location->Area = $listing['Area'];
                    $is_property_location->CommunityName = $listing['CommunityName'];
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
                    $is_property_location->update();
                } else {
                    $propertylocation = new PropertyLocation();
                    $propertylocation->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                    $propertylocation->MLSNumber = $listing['MLSNumber'];
                    $propertylocation->Area = $listing['Area'];
                    $propertylocation->CommunityName = $listing['CommunityName'];
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
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        Log::info('Queue Stop');
    }
}
