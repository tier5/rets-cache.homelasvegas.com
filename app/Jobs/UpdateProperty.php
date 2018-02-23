<?php

namespace App\Jobs;

use App\PropertyMiscellaneous;
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
        ini_set('max_execution_time', 3000000000);
        set_time_limit(0);
        ini_set('memory_limit', '521M');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Update Property queue Start');
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
                //Log::info('Get Result from Rets');
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
                if ($newPropertyDetails != null) {
                    //Update Lat Long
                    if ($newPropertyDetails->PublicAddress != $listing['PublicAddress']) {
                        try{
                            $formattedAddr = str_replace(' ', '+', $listing['PublicAddress']);
                            $final_address = $formattedAddr . '+' . $listing['PostalCode'];
                            $client = new Client();
                            $geocodeFromAddr = $client->request('GET','https://maps.googleapis.com/maps/api/geocode/json?address=' . $final_address . '&key=AIzaSyCpUfYECkxluIkMJpQtdbFBpxiFn2xEQD8');
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
                        $geocodeFromAddr = $client->request('GET','https://maps.googleapis.com/maps/api/geocode/json?address=' . $final_address . '&key=AIzaSyCpUfYECkxluIkMJpQtdbFBpxiFn2xEQD8');
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
                //Log::info('Property Additional');
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
                //Log::info('Property External');
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
                //Log::info('Property Feature');
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
                //Log::info('property_financial_details');
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
                //Log::info('Update Images');
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
                //Log::info('property_interior_features');
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
                $is_property_misscellaneous = PropertyMiscellaneous::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                if($is_property_misscellaneous == '') {
                    $is_property_misscellaneous = new PropertyMiscellaneous();
                    $is_property_misscellaneous->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                    $is_property_misscellaneous->MLSNumber = $listing['MLSNumber'];
                }
                $is_property_misscellaneous->AccessibilityFeatures = $listing['AccessibilityFeatures'];
                $is_property_misscellaneous->ActiveOpenHouseCount = $listing['ActiveOpenHouseCount'];
                $is_property_misscellaneous->AdditionalAUSoldTerms = $listing['AdditionalAUSoldTerms'];
                $is_property_misscellaneous->AdditionalPetRentYN = $listing['AdditionalPetRentYN'];
                $is_property_misscellaneous->AdministrationDeposit = $listing['AdministrationDeposit'];
                $is_property_misscellaneous->AdministrationFeeYN = $listing['AdministrationFeeYN'];
                $is_property_misscellaneous->AdministrationRefund = $listing['AdministrationRefund'];
                $is_property_misscellaneous->AmtOwnerWillCarry = $listing['AmtOwnerWillCarry'];
                $is_property_misscellaneous->ApplicationFeeAmount = $listing['ApplicationFeeAmount'];
                $is_property_misscellaneous->ApplicationFeeYN = $listing['ApplicationFeeYN'];
                $is_property_misscellaneous->ApproxAddlLivArea = $listing['ApproxAddlLivArea'];
                $is_property_misscellaneous->AppxSubfeeAmount = $listing['AppxSubfeeAmount'];
                $is_property_misscellaneous->AppxSubfeePymtTy = $listing['AppxSubfeePymtTy'];
                $is_property_misscellaneous->AssessedImpValue = $listing['AssessedImpValue'];
                $is_property_misscellaneous->AssessedLandValue = $listing['AssessedLandValue'];
                $is_property_misscellaneous->AssessmentBalance = $listing['AssessmentBalance'];
                $is_property_misscellaneous->AssessmentType = $listing['AssessmentType'];
                $is_property_misscellaneous->AssessmentYN = $listing['AssessmentYN'];
                $is_property_misscellaneous->AssociationFee2 = $listing['AssociationFee2'];
                $is_property_misscellaneous->AssociationFee2MQYN = $listing['AssociationFee2MQYN'];
                $is_property_misscellaneous->AssociationFeeMQYN = $listing['AssociationFeeMQYN'];
                $is_property_misscellaneous->AssociationFeeYN = $listing['AssociationFeeYN'];
                $is_property_misscellaneous->AssociationPhone = $listing['AssociationPhone'];
                $is_property_misscellaneous->AuctionDate = $listing['AuctionDate'];
                $is_property_misscellaneous->AuctionType = $listing['AuctionType'];
                $is_property_misscellaneous->BedandBathDownYN = $listing['BedandBathDownYN'];
                $is_property_misscellaneous->BedsTotal = $listing['BedsTotal'];
                $is_property_misscellaneous->BlockNumber = $listing['BlockNumber'];
                $is_property_misscellaneous->BonusSOYN = $listing['BonusSOYN'];
                $is_property_misscellaneous->BrandedVirtualTour = $listing['BrandedVirtualTour'];
                $is_property_misscellaneous->BuildingNumber = $listing['BuildingNumber'];
                $is_property_misscellaneous->BuyerPremium = $listing['BuyerPremium'];
                $is_property_misscellaneous->CableAvailable = $listing['CableAvailable'];
                $is_property_misscellaneous->CapRate = $listing['CapRate'];
                $is_property_misscellaneous->CarportDescription = $listing['CarportDescription'];
                $is_property_misscellaneous->Carports = $listing['Carports'];
                $is_property_misscellaneous->CashtoAssume = $listing['CashtoAssume'];
                $is_property_misscellaneous->CleaningDeposit = $listing['CleaningDeposit'];
                $is_property_misscellaneous->CleaningRefund = $listing['CleaningRefund'];
                $is_property_misscellaneous->CloseDate = $listing['CloseDate'];
                $is_property_misscellaneous->ClosePrice = $listing['ClosePrice'];
                $is_property_misscellaneous->CompactorYN = $listing['CompactorYN'];
                $is_property_misscellaneous->ConditionalDate = $listing['ConditionalDate'];
                $is_property_misscellaneous->CondoConversionYN = $listing['CondoConversionYN'];
                $is_property_misscellaneous->ConstructionEstimateEnd = $listing['ConstructionEstimateEnd'];
                $is_property_misscellaneous->ConstructionEstimateStart = $listing['ConstructionEstimateStart'];
                $is_property_misscellaneous->ContingencyDesc = $listing['ContingencyDesc'];
                $is_property_misscellaneous->ConvertedtoRealProperty = $listing['ConvertedtoRealProperty'];
                $is_property_misscellaneous->CostperUnit = $listing['CostperUnit'];
                $is_property_misscellaneous->CrossStreet = $listing['CrossStreet'];
                $is_property_misscellaneous->CurrentLoanAssumable = $listing['CurrentLoanAssumable'];
                $is_property_misscellaneous->DateAvailable = $listing['DateAvailable'];
                $is_property_misscellaneous->Deposit = $listing['Deposit'];
                $is_property_misscellaneous->Directions = $listing['Directions'];
                $is_property_misscellaneous->DishwasherDescription = $listing['DishwasherDescription'];
                $is_property_misscellaneous->DOM = $listing['DOM'];
                $is_property_misscellaneous->DomModifier_DateTime = $listing['DomModifier_DateTime'];
                $is_property_misscellaneous->DomModifier_Initial = $listing['DomModifier_Initial'];
                $is_property_misscellaneous->DomModifier_StatusRValue = $listing['DomModifier_StatusRValue'];
                $is_property_misscellaneous->DownPayment = $listing['DownPayment'];
                $is_property_misscellaneous->Electricity = $listing['Electricity'];
                $is_property_misscellaneous->ElevatorFloorNum = $listing['ElevatorFloorNum'];
                $is_property_misscellaneous->EnvironmentSurvey = $listing['EnvironmentSurvey'];
                $is_property_misscellaneous->EstCloLsedt = $listing['EstCloLsedt'];
                $is_property_misscellaneous->ExistingRent = $listing['ExistingRent'];
                $is_property_misscellaneous->ExpenseSource = $listing['ExpenseSource'];
                $is_property_misscellaneous->ExteriorDescription = $listing['ExteriorDescription'];
                $is_property_misscellaneous->Fireplace = $listing['Fireplace'];
                $is_property_misscellaneous->FirstEncumbranceAssumable = $listing['FirstEncumbranceAssumable'];
                $is_property_misscellaneous->FirstEncumbranceBalance = $listing['FirstEncumbranceBalance'];
                $is_property_misscellaneous->FirstEncumbrancePayment = $listing['FirstEncumbrancePayment'];
                $is_property_misscellaneous->FirstEncumbrancePmtDesc = $listing['FirstEncumbrancePmtDesc'];
                $is_property_misscellaneous->FirstEncumbranceRate = $listing['FirstEncumbranceRate'];
                $is_property_misscellaneous->FloodZone = $listing['FloodZone'];
                $is_property_misscellaneous->FurnishedYN = $listing['FurnishedYN'];
                $is_property_misscellaneous->GasDescription = $listing['GasDescription'];
                $is_property_misscellaneous->GravelRoad = $listing['GravelRoad'];
                $is_property_misscellaneous->GreenCertificationRating = $listing['GreenCertificationRating'];
                $is_property_misscellaneous->GreenCertifyingBody = $listing['GreenCertifyingBody'];
                $is_property_misscellaneous->GreenFeatures = $listing['GreenFeatures'];
                $is_property_misscellaneous->GreenYearCertified = $listing['GreenYearCertified'];
                $is_property_misscellaneous->GrossOperatingIncome = $listing['GrossOperatingIncome'];
                $is_property_misscellaneous->GrossRentMultiplier = $listing['GrossRentMultiplier'];
                $is_property_misscellaneous->GroundMountedYN = $listing['GroundMountedYN'];
                $is_property_misscellaneous->HandicapAdapted = $listing['HandicapAdapted'];
                $is_property_misscellaneous->HiddenFranchiseIDXOptInYN = $listing['HiddenFranchiseIDXOptInYN'];
                $is_property_misscellaneous->Highlights = $listing['Highlights'];
                $is_property_misscellaneous->HOAMinimumRentalCycle = $listing['HOAMinimumRentalCycle'];
                $is_property_misscellaneous->HOAYN = $listing['HOAYN'];
                $is_property_misscellaneous->HomeownerAssociationName = $listing['HomeownerAssociationName'];
                $is_property_misscellaneous->HomeownerAssociationPhoneNo = $listing['HomeownerAssociationPhoneNo'];
                $is_property_misscellaneous->HomeProtectionPlan = $listing['HomeProtectionPlan'];
                $is_property_misscellaneous->HotWater = $listing['HotWater'];
                $is_property_misscellaneous->IDX = $listing['IDX'];
                $is_property_misscellaneous->IDXOptInYN = $listing['IDXOptInYN'];
                $is_property_misscellaneous->InternetYN = $listing['InternetYN'];
                $is_property_misscellaneous->JuniorSuiteunder600sqft = $listing['JuniorSuiteunder600sqft'];
                $is_property_misscellaneous->KeyDeposit = $listing['KeyDeposit'];
                $is_property_misscellaneous->KeyRefund = $listing['KeyRefund'];
                $is_property_misscellaneous->KitchenCountertops = $listing['KitchenCountertops'];
                $is_property_misscellaneous->LandlordOwnerPays = $listing['LandlordOwnerPays'];
                $is_property_misscellaneous->LandUse = $listing['LandUse'];
                $is_property_misscellaneous->LastChangeTimestamp = $listing['LastChangeTimestamp'];
                $is_property_misscellaneous->LastChangeType = $listing['LastChangeType'];
                $is_property_misscellaneous->LastListPrice = $listing['LastListPrice'];
                $is_property_misscellaneous->LastStatus = $listing['LastStatus'];
                $is_property_misscellaneous->LeaseDescription = $listing['LeaseDescription'];
                $is_property_misscellaneous->LeaseOptionConsideredY = $listing['LeaseOptionConsideredY'];
                $is_property_misscellaneous->LeasePrice = $listing['LeasePrice'];
                $is_property_misscellaneous->LeedCertified = $listing['LeedCertified'];
                $is_property_misscellaneous->LegalDescription = $listing['LegalDescription'];
                $is_property_misscellaneous->Length = $listing['Length'];
                $is_property_misscellaneous->ListAgent_MUI = $listing['ListAgent_MUI'];
                $is_property_misscellaneous->ListingContractDate = $listing['ListingContractDate'];
                $is_property_misscellaneous->ListOffice_MUI = $listing['ListOffice_MUI'];
                $is_property_misscellaneous->ListOfficeMLSID = $listing['ListOfficeMLSID'];
                $is_property_misscellaneous->ListOfficePhone = $listing['ListOfficePhone'];
                $is_property_misscellaneous->LitigationType = $listing['LitigationType'];
                $is_property_misscellaneous->Location = $listing['Location'];
                $is_property_misscellaneous->LotDepth = $listing['LotDepth'];
                $is_property_misscellaneous->LotFront = $listing['LotFront'];
                $is_property_misscellaneous->LotFrontage = $listing['LotFrontage'];
                $is_property_misscellaneous->LotNumber = $listing['LotNumber'];
                $is_property_misscellaneous->Maintenance = $listing['Maintenance'];
                $is_property_misscellaneous->Management = $listing['Management'];
                $is_property_misscellaneous->Manufactured = $listing['Manufactured'];
                $is_property_misscellaneous->MapDescription = $listing['MapDescription'];
                $is_property_misscellaneous->MasterBedroomDownYN = $listing['MasterBedroomDownYN'];
                $is_property_misscellaneous->MasterPlan = $listing['MasterPlan'];
                $is_property_misscellaneous->MatrixModifiedDT = $listing['MatrixModifiedDT'];
                $is_property_misscellaneous->MediaRoomYN = $listing['MediaRoomYN'];
                $is_property_misscellaneous->MetroMapCoorXP = $listing['MetroMapCoorXP'];
                $is_property_misscellaneous->MetroMapPageXP = $listing['MetroMapPageXP'];
                $is_property_misscellaneous->MHYrBlt = $listing['MHYrBlt'];
                $is_property_misscellaneous->MLNumofPropIfforSale = $listing['MLNumofPropIfforSale'];
                $is_property_misscellaneous->MLS = $listing['MLS'];
                $is_property_misscellaneous->NetAcres = $listing['NetAcres'];
                $is_property_misscellaneous->NODDate = $listing['NODDate'];
                $is_property_misscellaneous->NOI = $listing['NOI'];
                $is_property_misscellaneous->NumberofFurnishedUnits = $listing['NumberofFurnishedUnits'];
                $is_property_misscellaneous->NumberofPets = $listing['NumberofPets'];
                $is_property_misscellaneous->NumBldgs = $listing['NumBldgs'];
                $is_property_misscellaneous->NumFloors = $listing['NumFloors'];
                $is_property_misscellaneous->NumGAcres = $listing['NumGAcres'];
                $is_property_misscellaneous->NumLoft = $listing['NumLoft'];
                $is_property_misscellaneous->NumofLoftAreas = $listing['NumofLoftAreas'];
                $is_property_misscellaneous->NumofParkingSpacesIncluded = $listing['NumofParkingSpacesIncluded'];
                $is_property_misscellaneous->NumParcels = $listing['NumParcels'];
                $is_property_misscellaneous->NumParking = $listing['NumParking'];
                $is_property_misscellaneous->NumStorageUnits = $listing['NumStorageUnits'];
                $is_property_misscellaneous->NumTerraces = $listing['NumTerraces'];
                $is_property_misscellaneous->NumUnits = $listing['NumUnits'];
                $is_property_misscellaneous->OffMarketDate = $listing['OffMarketDate'];
                $is_property_misscellaneous->OnSiteStaff = $listing['OnSiteStaff'];
                $is_property_misscellaneous->OnSiteStaffIncludes = $listing['OnSiteStaffIncludes'];
                $is_property_misscellaneous->OriginalListPrice = $listing['OriginalListPrice'];
                $is_property_misscellaneous->OtherDeposit = $listing['OtherDeposit'];
                $is_property_misscellaneous->OtherEncumbranceDesc = $listing['OtherEncumbranceDesc'];
                $is_property_misscellaneous->OtherIncomeDescription = $listing['OtherIncomeDescription'];
                $is_property_misscellaneous->OtherRefund = $listing['OtherRefund'];
                $is_property_misscellaneous->OvenFuel = $listing['OvenFuel'];
                $is_property_misscellaneous->OwnerManaged = $listing['OwnerManaged'];
                $is_property_misscellaneous->OwnersName = $listing['OwnersName'];
                $is_property_misscellaneous->OwnerWillCarry = $listing['OwnerWillCarry'];
                $is_property_misscellaneous->PackageAvailable = $listing['PackageAvailable'];
                $is_property_misscellaneous->ParkingLevel = $listing['ParkingLevel'];
                $is_property_misscellaneous->ParkingSpaceIDNum = $listing['ParkingSpaceIDNum'];
                $is_property_misscellaneous->PavedRoad = $listing['PavedRoad'];
                $is_property_misscellaneous->PendingDate = $listing['PendingDate'];
                $is_property_misscellaneous->PermittedPropertyManager = $listing['PermittedPropertyManager'];
                $is_property_misscellaneous->PerPetYN = $listing['PerPetYN'];
                $is_property_misscellaneous->PetDeposit = $listing['PetDeposit'];
                $is_property_misscellaneous->PetDescription = $listing['PetDescription'];
                $is_property_misscellaneous->PetRefund = $listing['PetRefund'];
                $is_property_misscellaneous->PetsAllowed = $listing['PetsAllowed'];
                $is_property_misscellaneous->PhotoExcluded = $listing['PhotoExcluded'];
                $is_property_misscellaneous->PhotoInstructions = $listing['PhotoInstructions'];
                $is_property_misscellaneous->PhotoModificationTimestamp = $listing['PhotoModificationTimestamp'];
                $is_property_misscellaneous->PoolLength = $listing['PoolLength'];
                $is_property_misscellaneous->PoolWidth = $listing['PoolWidth'];
                $is_property_misscellaneous->PostalCodePlus4 = $listing['PostalCodePlus4'];
                $is_property_misscellaneous->PreviousParcelNumber = $listing['PreviousParcelNumber'];
                $is_property_misscellaneous->PriceChangeTimestamp = $listing['PriceChangeTimestamp'];
                $is_property_misscellaneous->PriceChgDate = $listing['PriceChgDate'];
                $is_property_misscellaneous->PricePerAcre = $listing['PricePerAcre'];
                $is_property_misscellaneous->PrimaryViewDirection = $listing['PrimaryViewDirection'];
                $is_property_misscellaneous->ProjAmenitiesDescription = $listing['ProjAmenitiesDescription'];
                $is_property_misscellaneous->PropAmenitiesDescription = $listing['PropAmenitiesDescription'];
                $is_property_misscellaneous->PropertyCondition = $listing['PropertyCondition'];
                $is_property_misscellaneous->PropertyInsurance = $listing['PropertyInsurance'];
                $is_property_misscellaneous->ProviderKey = $listing['ProviderKey'];
                $is_property_misscellaneous->ProviderModificationTimestamp = $listing['ProviderModificationTimestamp'];
                $is_property_misscellaneous->Range = $listing['Range'];
                $is_property_misscellaneous->RATIO_ClosePrice_By_ListPrice = $listing['RATIO_ClosePrice_By_ListPrice'];
                $is_property_misscellaneous->RATIO_ClosePrice_By_OriginalListPrice = $listing['RATIO_ClosePrice_By_OriginalListPrice'];
                $is_property_misscellaneous->RefrigeratorDescription = $listing['RefrigeratorDescription'];
                $is_property_misscellaneous->RentedPrice = $listing['RentedPrice'];
                $is_property_misscellaneous->RentRange = $listing['RentRange'];
                $is_property_misscellaneous->RentTermsDescription = $listing['RentTermsDescription'];
                $is_property_misscellaneous->Road = $listing['Road'];
                $is_property_misscellaneous->SaleOfficeBonusYN = $listing['SaleOfficeBonusYN'];
                $is_property_misscellaneous->SaleType = $listing['SaleType'];
                $is_property_misscellaneous->SecondEncumbranceAssumable = $listing['SecondEncumbranceAssumable'];
                $is_property_misscellaneous->SecondEncumbranceBalance = $listing['SecondEncumbranceBalance'];
                $is_property_misscellaneous->SecondEncumbrancePayment = $listing['SecondEncumbrancePayment'];
                $is_property_misscellaneous->SecondEncumbrancePmtDesc = $listing['SecondEncumbrancePmtDesc'];
                $is_property_misscellaneous->SecondEncumbranceRate = $listing['SecondEncumbranceRate'];
                $is_property_misscellaneous->Section = $listing['Section'];
                $is_property_misscellaneous->Section8ConsideredYN = $listing['Section8ConsideredYN'];
                $is_property_misscellaneous->Security = $listing['Security'];
                $is_property_misscellaneous->SecurityDeposit = $listing['SecurityDeposit'];
                $is_property_misscellaneous->SecurityRefund = $listing['SecurityRefund'];
                $is_property_misscellaneous->SellerContribution = $listing['SellerContribution'];
                $is_property_misscellaneous->SellingAgent_MUI = $listing['SellingAgent_MUI'];
                $is_property_misscellaneous->SellingAgentDirectWorkPhone = $listing['SellingAgentDirectWorkPhone'];
                $is_property_misscellaneous->SellingAgentFullName = $listing['SellingAgentFullName'];
                $is_property_misscellaneous->SellingAgentMLSID = $listing['SellingAgentMLSID'];
                $is_property_misscellaneous->SellingOffice_MUI = $listing['SellingOffice_MUI'];
                $is_property_misscellaneous->SellingOfficeMLSID = $listing['SellingOfficeMLSID'];
                $is_property_misscellaneous->SellingOfficeName = $listing['SellingOfficeName'];
                $is_property_misscellaneous->SellingOfficePhone = $listing['SellingOfficePhone'];
                $is_property_misscellaneous->SeparateMeter = $listing['SeparateMeter'];
                $is_property_misscellaneous->ServiceContractInc = $listing['ServiceContractInc'];
                $is_property_misscellaneous->ServicesAvailableOnSite = $listing['ServicesAvailableOnSite'];
                $is_property_misscellaneous->ShowingAgentPublicID = $listing['ShowingAgentPublicID'];
                $is_property_misscellaneous->SIDLIDAnnualAmount = $listing['SIDLIDAnnualAmount'];
                $is_property_misscellaneous->SIDLIDBalance = $listing['SIDLIDBalance'];
                $is_property_misscellaneous->SoldAppraisal_NUMBER = $listing['SoldAppraisal_NUMBER'];
                $is_property_misscellaneous->SoldBalloonAmt = $listing['SoldBalloonAmt'];
                $is_property_misscellaneous->SoldBalloonDue = $listing['SoldBalloonDue'];
                $is_property_misscellaneous->SoldDownPayment = $listing['SoldDownPayment'];
                $is_property_misscellaneous->SoldLeaseDescription = $listing['SoldLeaseDescription'];
                $is_property_misscellaneous->SoldOWCAmt = $listing['SoldOWCAmt'];
                $is_property_misscellaneous->SoldTerm = $listing['SoldTerm'];
                $is_property_misscellaneous->StateOrProvince = $listing['StateOrProvince'];
                $is_property_misscellaneous->StatusChangeTimestamp = $listing['StatusChangeTimestamp'];
                $is_property_misscellaneous->StatusContractualSearchDate = $listing['StatusContractualSearchDate'];
                $is_property_misscellaneous->StatusUpdate = $listing['StatusUpdate'];
                $is_property_misscellaneous->StorageSecure = $listing['StorageSecure'];
                $is_property_misscellaneous->StorageUnitDesc = $listing['StorageUnitDesc'];
                $is_property_misscellaneous->StorageUnitDim = $listing['StorageUnitDim'];
                $is_property_misscellaneous->StreetDirPrefix = $listing['StreetDirPrefix'];
                $is_property_misscellaneous->StreetDirSuffix = $listing['StreetDirSuffix'];
                $is_property_misscellaneous->StreetSuffix = $listing['StreetSuffix'];
                $is_property_misscellaneous->StudioYN = $listing['StudioYN'];
                $is_property_misscellaneous->Style = $listing['Style'];
                $is_property_misscellaneous->SubjecttoFIRPTAYN = $listing['SubjecttoFIRPTAYN'];
                $is_property_misscellaneous->Table = $listing['Table'];
                $is_property_misscellaneous->TempOffMarketDate = $listing['TempOffMarketDate'];
                $is_property_misscellaneous->TerraceLocation = $listing['TerraceLocation'];
                $is_property_misscellaneous->TerraceTotalSqft = $listing['TerraceTotalSqft'];
                $is_property_misscellaneous->TerrainDescription = $listing['TerrainDescription'];
                $is_property_misscellaneous->TotalFloors = $listing['TotalFloors'];
                $is_property_misscellaneous->TotalNumofParkingSpaces = $listing['TotalNumofParkingSpaces'];
                $is_property_misscellaneous->TowerName = $listing['TowerName'];
                $is_property_misscellaneous->Town = $listing['Town'];
                $is_property_misscellaneous->Township = $listing['Township'];
                $is_property_misscellaneous->TransactionType = $listing['TransactionType'];
                $is_property_misscellaneous->Trash = $listing['Trash'];
                $is_property_misscellaneous->TStatusDate = $listing['TStatusDate'];
                $is_property_misscellaneous->TypeOwnerWillCarry = $listing['TypeOwnerWillCarry'];
                $is_property_misscellaneous->UnitCount = $listing['UnitCount'];
                $is_property_misscellaneous->UnitDescription = $listing['UnitDescription'];
                $is_property_misscellaneous->UnitNumber = $listing['UnitNumber'];
                $is_property_misscellaneous->UnitPoolIndoorYN = $listing['UnitPoolIndoorYN'];
                $is_property_misscellaneous->UnitSpaIndoor = $listing['UnitSpaIndoor'];
                $is_property_misscellaneous->Utilities = $listing['Utilities'];
                $is_property_misscellaneous->UtilitiesIncl = $listing['UtilitiesIncl'];
                $is_property_misscellaneous->Views = $listing['Views'];
                $is_property_misscellaneous->Washer = $listing['Washer'];
                $is_property_misscellaneous->WasherDryerDescription = $listing['WasherDryerDescription'];
                $is_property_misscellaneous->WasherDryerIncluded = $listing['WasherDryerIncluded'];
                $is_property_misscellaneous->WaterHeaterDescription = $listing['WaterHeaterDescription'];
                $is_property_misscellaneous->WeightLimit = $listing['WeightLimit'];
                $is_property_misscellaneous->Width = $listing['Width'];
                $is_property_misscellaneous->YearlyOperatingExpense = $listing['YearlyOperatingExpense'];
                $is_property_misscellaneous->YearlyOperatingIncome = $listing['YearlyOperatingIncome'];
                $is_property_misscellaneous->YearlyOtherIncome = $listing['YearlyOtherIncome'];
                $is_property_misscellaneous->YrsRemaining = $listing['YrsRemaining'];
                $is_property_misscellaneous->ZoningAuthority = $listing['ZoningAuthority'];
                $is_property_misscellaneous->save();
                //Log::info('Property Location');
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
