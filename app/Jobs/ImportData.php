<?php

namespace App\Jobs;

use App\Citylist;
use App\PropertyImage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use \DB;
use App\PropertyDetail;
use App\PropertyAdditional;
use App\PropertyExternalFeature;
use App\PropertyFeature;
use App\PropertyFinancialDetail;
use App\PropertyInteriorFeature;
use App\PropertyLocation;
use App\PropertyLatLong;
use App\PropertyMiscellaneous;
use App\PropertyAdditionalDetail;
use App\PropertyAdditionalFeature;
use App\PropertyFinancialAdditional;
use App\PropertyInsurance;
use App\PropertyInteriorDetail;
use App\PropertyOtherInformation;
use App\PropertySellingDetails;

class ImportData implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $city;
    public $limit;
    public $offset;
    public $query_city;
    public function __construct($city,$limit,$offset,$query_city)
    {
        $this->city = $city;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->query_city = $query_city;
        ini_set('max_execution_time', 30000000);
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Import Data Queue Start for '.$this->city.'  query =  '.$this->query_city.' from '.$this->offset);
        try {
            $search_result = array();
            $data = array();
            $cityArray = array(
                'ALAMO' => 'Alamo',
                'ARMAGOSA' => 'Amargosa',
                'BEATTY' => 'Beatty',
                'BLUEDIAM' => 'Blue Diamond',
                'BOULDERC' => 'Boulder City',
                'CALIENTE' => 'Caliente',
                'CALNEVAR' => 'Cal-Nev-Ari',
                'COLDCRK' => 'Cold Creek',
                'ELY' => 'Ely',
                'GLENDALE' => 'Glendale',
                'GOODSPRG' => 'Goodsprings',
                'HENDERSON' => 'Henderson',
                'INDIANSP' => 'Indian Springs',
                'JEAN' => 'Jean',
                'LASVEGAS' => 'Las Vegas',
                'LAUGHLIN' => 'Laughlin',
                'LOGANDAL' => 'Logandale',
                'MCGILL' => 'Mc Gill',
                'MESQUITE' => 'Mesquite',
                'MOAPA' => 'Moapa',
                'MTNSPRG' => 'Mountain Spring',
                'NORTHLAS' => 'North Las Vegas',
                'OTHER' => 'Other',
                'OVERTON' => 'Overton',
                'PAHRUMP' => 'Pahrump',
                'PALMGRDNS' => 'Palm Gardens',
                'PANACA' => 'Panaca',
                'PIOCHE' => 'Pioche',
                'SANDYVLY' => 'Sandy Valley',
                'SEARCHLT' => 'Searchlight',
                'TONOPAH' => 'Tonopah',
                'URSINE' => 'Ursine'
            );
            $rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
            $rets_username = "neal";
            $rets_password = "glvar";
            $rets = new \phRETS();
            $rets->AddHeader("RETS-Version", "RETS/1.7.2");
            $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
            if ($connect) {
                Log::info('connected');
                if ($this->city != "") {
                    $data['city'] = $this->city;
                    $query = $this->query_city;
                }
                $limit = 10;
                $offset = 1;
                $data['limit'] = $limit;
                if ($offset > 0) {
                    $data['offset_val'] = $offset;
                }
                $data['count'] = $data['offset_val'] + $data['limit'];
                $search = $rets->SearchQuery("Property", "Listing", $query, array("StandardNames" => 0, 'Limit' => $this->limit, 'Offset' => $this->offset));
                $result_count = $rets->TotalRecordsFound();
                $city=Citylist::where('name',$this->city)->first();
                $city->total = $result_count;
                $city->update();
                $total_records = $pages = ceil($result_count / $limit);
                $search_result = array();
                $key = 0;
                $rowCount = 0;
                while ($listing = $rets->FetchRow($search)) {
                    try{
                        $rowCount++;
                        $photos = $rets->GetObject("Property", "LargePhoto", $listing['Matrix_Unique_ID'], "*", 0);
                        $deleteImage = PropertyImage::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->delete();
                        $contentType = $property_image = '';
                        $content_id = $object_id = $Success = 0;
                        foreach ($photos as $keyImage => $photo) {
                            if ($keyImage > 0) {
                                break;
                            }
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
                                $search_result[$key]['contentType'] = $photo['Content-Type'];
                                $search_result[$key]['property_image'] = $photo['Data'];
                            } else {
                                $search_result[$key]['contentType'] = '';
                                $search_result[$key]['property_image'] = '';
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
                        //-------------------------------------
                        $is_property = PropertyDetail::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();
                        if ($is_property) {
                            $is_property->ListPrice = $listing['ListPrice'];
                            $is_property->Status = $listing['Status'];
                            $is_property->BedroomsTotalPossibleNum = $BedroomsTotalPossibleNum;
                            $is_property->BathsTotal = $BathsTotal;
                            $is_property->BathsHalf = $BathsHalf;
                            $is_property->BathsFull = $BathsFull;
                            $is_property->NumAcres = $NumAcres;
                            $is_property->SqFtTotal = $SqFtTotal;
                            $is_property->StreetNumber = $listing['StreetNumber'];
                            $is_property->StreetName = $listing['StreetName'];
                            $is_property->City = $data['city'];
                            $is_property->MLSNumber = $listing['MLSNumber'];
                            $is_property->PostalCode = $listing['PostalCode'];
                            $is_property->PhotoCount = $listing['PhotoCount'];
                            $is_property->PublicAddress = $listing['PublicAddress'];
                            $is_property->VirtualTourLink = $listing['VirtualTourLink'];
                            $is_property->OriginalEntryTimestamp = $listing['OriginalEntryTimestamp'];
                            $is_property->save();
                        } else {
                            $property = new PropertyDetail();
                            $property->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                            $property->ListPrice = $listing['ListPrice'];
                            $property->Status = $listing['Status'];
                            $property->BedroomsTotalPossibleNum = $BedroomsTotalPossibleNum;
                            $property->BathsTotal = $BathsTotal;
                            $property->BathsHalf = $BathsHalf;
                            $property->BathsFull = $BathsFull;
                            $property->NumAcres = $NumAcres;
                            $property->SqFtTotal = $SqFtTotal;
                            $property->StreetNumber = $listing['StreetNumber'];
                            $property->StreetName = $listing['StreetName'];
                            $property->City = $data['city'];
                            $property->MLSNumber = $listing['MLSNumber'];
                            $property->PostalCode = $listing['PostalCode'];
                            $property->PhotoCount = $listing['PhotoCount'];
                            $property->PublicAddress = $listing['PublicAddress'];
                            $property->VirtualTourLink = $listing['VirtualTourLink'];
                            $property->OriginalEntryTimestamp = $listing['OriginalEntryTimestamp'];
                            $property->save();
                            $p = $city->inserted;
                            $city->inserted = $p+1;
                            $city->update();
                        }
                        $is_property_feature = PropertyFeature::where('Matrix_Unique_ID', '=', $listing['Matrix_Unique_ID'])->first();
                        if ($is_property_feature) {
                            $is_property_feature->YearBuilt = $YearBuilt;
                            $is_property_feature->PropertyType = $listing['PropertyType'];
                            $is_property_feature->PropertySubType = $listing['PropertySubType'];
                            $is_property_feature->CountyOrParish = $listing['CountyOrParish'];
                            $is_property_feature->Zoning = $listing['Zoning'];
                            $is_property_feature->MLSNumber = $listing['MLSNumber'];
                            $is_property->save();
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
                            $is_property_external_feature->save();
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
                            $is_property_additional->PublicAddressYN = isset($listing['PublicAddressYN']) && $listing['PublicAddressYN'] != '' ? $listing['PublicAddressYN'] : 0;
                            $is_property_additional->PublicRemarks = $listing['PublicRemarks'];
                            $is_property_additional->ListAgentMLSID = $listing['ListAgentMLSID'];
                            $is_property_additional->ListAgentFullName = $listing['ListAgentFullName'];
                            $is_property_additional->ListOfficeName = $listing['ListOfficeName'];
                            $is_property_additional->ListAgentDirectWorkPhone = $listing['ListAgentDirectWorkPhone'];
                            $is_property_additional->RealtorYN = $RealtorYN;
                            $is_property_additional->RefrigeratorYN = $RefrigeratorYN;
                            $is_property_additional->Spa = $listing['Spa'];
                            $is_property_additional->SpaDescription = $listing['SpaDescription'];
                            $is_property_additional->YearRoundSchoolYN = $YearRoundSchoolYN;
                            $is_property_additional->save();
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
                            $propertyadditional->PublicAddressYN = isset($listing['PublicAddressYN']) && $listing['PublicAddressYN'] != '' ? $listing['PublicAddressYN'] : 0;
                            $propertyadditional->PublicRemarks = $listing['PublicRemarks'];
                            $propertyadditional->ListAgentMLSID = $listing['ListAgentMLSID'];
                            $propertyadditional->ListAgentFullName = $listing['ListAgentFullName'];
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
                            $is_property_financial_detail->save();
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
                            $is_property_interior_feature->save();
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
                            $is_property_location->save();
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
                        //property Miscellaneous
                        try {
                            $is_property_misscellaneous = PropertyMiscellaneous::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                            if($is_property_misscellaneous == '') {
                                $is_property_misscellaneous = new PropertyMiscellaneous();
                                $is_property_misscellaneous->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                $is_property_misscellaneous->MLSNumber = $listing['MLSNumber'];
                            }
                            $is_property_misscellaneous->NetAcres = $listing['NetAcres'];
                            $is_property_misscellaneous->NODDate = (isset($listing['NODDate']) && $listing['NODDate'] == '') ? null : $listing['NODDate'];
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
                            $is_property_misscellaneous->OnSiteStaff = isset($listing['OnSiteStaff']) && $listing['OnSiteStaff'] ? $listing['OnSiteStaff'] : 0;
                            $is_property_misscellaneous->Range = $listing['Range'];
                            $is_property_misscellaneous->RATIO_ClosePrice_By_ListPrice = $listing['RATIO_ClosePrice_By_ListPrice'];
                            $is_property_misscellaneous->RATIO_ClosePrice_By_OriginalListPrice = $listing['RATIO_ClosePrice_By_OriginalListPrice'];
                            $is_property_misscellaneous->RefrigeratorDescription = $listing['RefrigeratorDescription'];
                            $is_property_misscellaneous->RentedPrice = $listing['RentedPrice'];
                            $is_property_misscellaneous->RentRange = $listing['RentRange'];
                            $is_property_misscellaneous->StreetDirPrefix = $listing['StreetDirPrefix'];
                            $is_property_misscellaneous->StreetDirSuffix = $listing['StreetDirSuffix'];
                            $is_property_misscellaneous->StreetSuffix = $listing['StreetSuffix'];
                            $is_property_misscellaneous->StudioYN = isset($listing['StudioYN']) && $listing['StudioYN'] != '' ? $listing['StudioYN'] : '';
                            $is_property_misscellaneous->Style = $listing['Style'];
                            $is_property_misscellaneous->SubjecttoFIRPTAYN = isset($listing['SubjecttoFIRPTAYN']) && $listing['SubjecttoFIRPTAYN'] ? $listing['SubjecttoFIRPTAYN'] : 0;
                            $is_property_misscellaneous->YearlyOperatingExpense = $listing['YearlyOperatingExpense'];
                            $is_property_misscellaneous->YearlyOperatingIncome = $listing['YearlyOperatingIncome'];
                            $is_property_misscellaneous->YearlyOtherIncome = $listing['YearlyOtherIncome'];
                            $is_property_misscellaneous->YrsRemaining = $listing['YrsRemaining'];
                            $is_property_misscellaneous->save();
                        } catch (\Exception $me) {
                            Log::info("Error in Property Miscellaneous : " .$me->getMessage());
                        }
                        
                        // Property Additional Detail 
                        try {
                            $is_property_additional_details = PropertyAdditionalDetail::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                            if($is_property_additional_details == '') {
                                $is_property_additional_details = new PropertyAdditionalDetail();
                                $is_property_additional_details->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                $is_property_additional_details->MLSNumber = $listing['MLSNumber'];
                            }
                            $is_property_additional_details->BedandBathDownYN = isset($listing['BedandBathDownYN']) && $listing['BedandBathDownYN'] != '' ? $listing['BedandBathDownYN'] : 0;
                            $is_property_additional_details->BedsTotal = $listing['BedsTotal'];
                            $is_property_additional_details->BlockNumber = $listing['BlockNumber'];
                            $is_property_additional_details->BonusSOYN = isset($listing['BonusSOYN']) && $listing['BonusSOYN'] != '' ? $listing['BonusSOYN'] : 0;
                            $is_property_additional_details->BrandedVirtualTour = $listing['BrandedVirtualTour'];
                            $is_property_additional_details->BuildingNumber = $listing['BuildingNumber'];
                            $is_property_additional_details->BuyerPremium = $listing['BuyerPremium'];
                            $is_property_additional_details->CableAvailable = isset($listing['CableAvailable']) && $listing['CableAvailable'] != '' ? $listing['CableAvailable'] : 0;
                            $is_property_additional_details->CapRate = $listing['CapRate'];
                            $is_property_additional_details->CarportDescription = $listing['CarportDescription'];
                            $is_property_additional_details->Carports = $listing['Carports'];
                            $is_property_additional_details->CashtoAssume = $listing['CashtoAssume'];
                            $is_property_additional_details->CleaningDeposit = $listing['CleaningDeposit'];
                            $is_property_additional_details->CleaningRefund = $listing['CleaningRefund'];
                            $is_property_additional_details->CloseDate = (isset($listing['CloseDate']) && $listing['CloseDate'] == '') ? null : $listing['CloseDate'];
                            $is_property_additional_details->ClosePrice = $listing['ClosePrice'];
                            $is_property_additional_details->CompactorYN = isset($listing['CompactorYN']) && $listing['CompactorYN'] != '' ? $listing['CompactorYN'] : 0;
                            $is_property_additional_details->ConditionalDate = (isset($listing['ConditionalDate']) && $listing['ConditionalDate'] == '') ? null : $listing['ConditionalDate'];
                            $is_property_additional_details->CondoConversionYN = isset($listing['CondoConversionYN']) && $listing['CondoConversionYN'] != '' ? $listing['CondoConversionYN'] : 0;
//                            Log::info('ConstructionEstimateEnd value :'. $listing['ConstructionEstimateEnd']);
                            $is_property_additional_details->ConstructionEstimateEnd = isset($listing['ConstructionEstimateEnd']) && $listing['ConstructionEstimateEnd'] != '' ? $listing['ConstructionEstimateEnd'] : 0 ;
                            $is_property_additional_details->ConstructionEstimateStart = (isset($listing['ConstructionEstimateStart']) && $listing['ConstructionEstimateStart'] == '') ? null : $listing['ConstructionEstimateStart'];
                            $is_property_additional_details->ContingencyDesc = $listing['ContingencyDesc'];
                            $is_property_additional_details->ConvertedtoRealProperty = isset($listing['ConvertedtoRealProperty']) && $listing['ConvertedtoRealProperty'] != '' ? $listing['ConvertedtoRealProperty'] : 0;
                            $is_property_additional_details->CostperUnit = $listing['CostperUnit'];
                            $is_property_additional_details->CrossStreet = $listing['CrossStreet'];
                            $is_property_additional_details->CurrentLoanAssumable = isset($listing['CurrentLoanAssumable']) && $listing['CurrentLoanAssumable'] != '' ? $listing['CurrentLoanAssumable'] : 0;
                            $is_property_additional_details->DateAvailable = (isset($listing['DateAvailable']) && $listing['DateAvailable'] == '') ? null : $listing['DateAvailable'];
                            $is_property_additional_details->Deposit = $listing['Deposit'];
                            $is_property_additional_details->Directions = $listing['Directions'];
                            $is_property_additional_details->DishwasherDescription = $listing['DishwasherDescription'];
                            $is_property_additional_details->DOM = $listing['DOM'];
                            $is_property_additional_details->DomModifier_DateTime = (isset($listing['DomModifier_DateTime']) && $listing['DomModifier_DateTime'] == '') ? null : $listing['DomModifier_DateTime'];
                            $is_property_additional_details->DomModifier_Initial = $listing['DomModifier_Initial'];
                            $is_property_additional_details->DomModifier_StatusRValue = $listing['DomModifier_StatusRValue'];
                            $is_property_additional_details->DownPayment = $listing['DownPayment'];
                            $is_property_additional_details->save();
                        } catch (\Exception $ex) {
                            Log::info("Error in Property Additional Detail : " .$ex->getMessage());
                        }
                        
                        // Property Additional Feature
                        try {
                            $is_property_additional_feature = PropertyAdditionalFeature::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                            if($is_property_additional_feature == '') {
                                $is_property_additional_feature = new PropertyAdditionalFeature();
                                $is_property_additional_feature->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                $is_property_additional_feature->MLSNumber = $listing['MLSNumber'];
                            }
                            $is_property_additional_feature->AccessibilityFeatures = $listing['AccessibilityFeatures'];
                            $is_property_additional_feature->ActiveOpenHouseCount = $listing['ActiveOpenHouseCount'];
                            $is_property_additional_feature->AdditionalAUSoldTerms = $listing['AdditionalAUSoldTerms'];
                            $is_property_additional_feature->AdditionalPetRentYN = isset($listing['AdditionalPetRentYN']) && $listing['AdditionalPetRentYN'] != '' ? $listing['AdditionalPetRentYN'] : 0;
                            $is_property_additional_feature->AdministrationDeposit = $listing['AdministrationDeposit'];
                            $is_property_additional_feature->AdministrationFeeYN = isset($listing['AdministrationFeeYN']) && $listing['AdministrationFeeYN'] != '' ? $listing['AdministrationFeeYN'] : 0;
                            $is_property_additional_feature->AdministrationRefund = $listing['AdministrationRefund'];
                            $is_property_additional_feature->AmtOwnerWillCarry = $listing['AmtOwnerWillCarry'];
                            $is_property_additional_feature->ApplicationFeeAmount = $listing['ApplicationFeeAmount'];
                            $is_property_additional_feature->ApplicationFeeYN = isset($listing['ApplicationFeeYN']) && $listing['ApplicationFeeYN'] != '' ? $listing['ApplicationFeeYN'] : 0;
                            $is_property_additional_feature->ApproxAddlLivArea = $listing['ApproxAddlLivArea'];
                            $is_property_additional_feature->AppxSubfeeAmount = $listing['AppxSubfeeAmount'];
                            $is_property_additional_feature->AppxSubfeePymtTy = $listing['AppxSubfeePymtTy'];
                            $is_property_additional_feature->AssessedImpValue = $listing['AssessedImpValue'];
                            $is_property_additional_feature->AssessedLandValue = $listing['AssessedLandValue'];
                            $is_property_additional_feature->AssessmentBalance = $listing['AssessmentBalance'];
                            $is_property_additional_feature->AssessmentType = $listing['AssessmentType'];
                            $is_property_additional_feature->AssessmentYN = isset($listing['AssessmentYN']) && $listing['AssessmentYN'] != '' ? $listing['AssessmentYN'] : 0;
                            $is_property_additional_feature->AssociationFee2 = $listing['AssociationFee2'];
                            $is_property_additional_feature->AssociationFee2MQYN = $listing['AssociationFee2MQYN'];
                            $is_property_additional_feature->AssociationFeeMQYN = $listing['AssociationFeeMQYN'];
                            $is_property_additional_feature->AssociationFeeYN = isset($listing['AssociationFeeYN']) && $listing['BedandBathDownYN'] != '' ? $listing['BedandBathDownYN'] : 0;
                            $is_property_additional_feature->AssociationPhone = $listing['AssociationPhone'];
                            $is_property_additional_feature->AuctionDate = (isset($listing['AuctionDate']) && $listing['AuctionDate'] == '') ? null : $listing['AuctionDate'];
                            $is_property_additional_feature->AuctionType = $listing['AuctionType'];
                            $is_property_additional_feature->save();
                        } catch (\Exception $ex) {
                            Log::info("Error in Property Additional Feature : " .$ex->getMessage());
                        }
                        
                        // Property Financial Additional
                        try {
                            $is_property_financial_additional = PropertyFinancialAdditional::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                            if($is_property_financial_additional == '') {
                                $is_property_financial_additional = new PropertyFinancialAdditional();
                                $is_property_financial_additional->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                $is_property_financial_additional->MLSNumber = $listing['MLSNumber'];
                            }
                            $is_property_financial_additional->Electricity = $listing['Electricity'];
                            $is_property_financial_additional->ElevatorFloorNum = $listing['ElevatorFloorNum'];
                            $is_property_financial_additional->EnvironmentSurvey = isset($listing['EnvironmentSurvey']) && $listing['EnvironmentSurvey'] != '' ? $listing['EnvironmentSurvey'] : 0;
                            $is_property_financial_additional->EstCloLsedt = (isset($listing['EstCloLsedt']) && $listing['EstCloLsedt'] == '') ? null : $listing['EstCloLsedt'];
                            $is_property_financial_additional->ExistingRent = $listing['ExistingRent'];
                            $is_property_financial_additional->ExpenseSource = $listing['ExpenseSource'];
                            $is_property_financial_additional->ExteriorDescription = $listing['ExteriorDescription'];
                            $is_property_financial_additional->Fireplace = isset($listing['Fireplace']) && $listing['Fireplace'] != '' ? $listing['Fireplace'] : 0;
                            $is_property_financial_additional->FirstEncumbranceAssumable = $listing['FirstEncumbranceAssumable'];
                            $is_property_financial_additional->FirstEncumbranceBalance = $listing['FirstEncumbranceBalance'];
                            $is_property_financial_additional->FirstEncumbrancePayment = $listing['FirstEncumbrancePayment'];
                            $is_property_financial_additional->FirstEncumbrancePmtDesc = $listing['FirstEncumbrancePmtDesc'];
                            $is_property_financial_additional->FirstEncumbranceRate = $listing['FirstEncumbranceRate'];
                            $is_property_financial_additional->FloodZone = $listing['FloodZone'];
                            $is_property_financial_additional->FurnishedYN = isset($listing['FurnishedYN']) && $listing['FurnishedYN'] != '' ? $listing['FurnishedYN'] : 0;
                            $is_property_financial_additional->GasDescription = $listing['GasDescription'];
                            $is_property_financial_additional->GravelRoad = $listing['GravelRoad'];
                            $is_property_financial_additional->GreenCertificationRating = $listing['GreenCertificationRating'];
                            $is_property_financial_additional->GreenCertifyingBody = $listing['GreenCertifyingBody'];
                            $is_property_financial_additional->GreenFeatures = $listing['GreenFeatures'];
                            $is_property_financial_additional->GreenYearCertified = $listing['GreenYearCertified'];
                            $is_property_financial_additional->GrossOperatingIncome = $listing['GrossOperatingIncome'];
                            $is_property_financial_additional->GrossRentMultiplier = $listing['GrossRentMultiplier'];
                            $is_property_financial_additional->GroundMountedYN = isset($listing['GroundMountedYN']) && $listing['GroundMountedYN'] != '' ? $listing['GroundMountedYN'] : 0;
                            $is_property_financial_additional->HandicapAdapted = isset($listing['HandicapAdapted']) && $listing['HandicapAdapted'] != '' ? $listing['HandicapAdapted'] : 0;
                            $is_property_financial_additional->HiddenFranchiseIDXOptInYN = isset($listing['HiddenFranchiseIDXOptInYN']) && $listing['HiddenFranchiseIDXOptInYN'] != '' ? $listing['HiddenFranchiseIDXOptInYN'] : 0;
                            $is_property_financial_additional->Highlights = $listing['Highlights'];
                            $is_property_financial_additional->HOAMinimumRentalCycle = $listing['HOAMinimumRentalCycle'];
                            $is_property_financial_additional->HOAYN = isset($listing['HOAYN']) && $listing['HOAYN'] != '' ? $listing['HOAYN'] :  0;
                            $is_property_financial_additional->HomeownerAssociationName = $listing['HomeownerAssociationName'];
                            $is_property_financial_additional->HomeownerAssociationPhoneNo = $listing['HomeownerAssociationPhoneNo'];
                            $is_property_financial_additional->HomeProtectionPlan = isset($listing['HomeProtectionPlan']) && $listing['HomeProtectionPlan'] != '' ? $listing['HomeProtectionPlan'] : 0;
                            $is_property_financial_additional->HotWater = $listing['HotWater'];
                            $is_property_financial_additional->IDX = $listing['IDX'];
                            $is_property_financial_additional->IDXOptInYN = isset($listing['IDXOptInYN']) && $listing['IDXOptInYN'] != '' ? $listing['IDXOptInYN'] : 0;
                            $is_property_financial_additional->InternetYN = isset($listing['InternetYN']) && $listing['InternetYN'] != '' ? $listing['InternetYN'] : 0;
                            $is_property_financial_additional->JuniorSuiteunder600sqft = isset($listing['JuniorSuiteunder600sqft']) && $listing['JuniorSuiteunder600sqft'] != '' ? $listing['JuniorSuiteunder600sqft'] : 0;
                            $is_property_financial_additional->save();
                        } catch (\Exception $ex) {
                            Log::info("Error in Property Financial Additional : " .$ex->getMessage());
                        }
                        
                        // Property Insurance
                        try {
                            $is_property_insurance = PropertyInsurance::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                            if($is_property_insurance == '') {
                                $is_property_insurance = new PropertyInsurance();
                                $is_property_insurance->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                $is_property_insurance->MLSNumber = $listing['MLSNumber'];
                            }
                            $is_property_insurance->OnSiteStaffIncludes = $listing['OnSiteStaffIncludes'];
                            $is_property_insurance->OriginalListPrice = $listing['OriginalListPrice'];
                            $is_property_insurance->OtherDeposit = $listing['OtherDeposit'];
                            $is_property_insurance->OtherEncumbranceDesc = $listing['OtherEncumbranceDesc'];
                            $is_property_insurance->OtherIncomeDescription = $listing['OtherIncomeDescription'];
                            $is_property_insurance->OtherRefund = $listing['OtherRefund'];
                            $is_property_insurance->OvenFuel = $listing['OvenFuel'];
                            $is_property_insurance->OwnerManaged = isset($listing['OwnerManaged']) && $listing['OwnerManaged'] != '' ? $listing['OwnerManaged'] : 0;
                            $is_property_insurance->OwnersName = $listing['OwnersName'];
                            $is_property_insurance->OwnerWillCarry = isset($listing['OwnerWillCarry']) && $listing['OwnerWillCarry'] != '' ? $listing['OwnerWillCarry'] : 0;
                            $is_property_insurance->PackageAvailable = isset($listing['PackageAvailable']) && $listing['PackageAvailable'] != '' ? $listing['PackageAvailable'] : 0;
                            $is_property_insurance->ParkingLevel = $listing['ParkingLevel'];
                            $is_property_insurance->ParkingSpaceIDNum = $listing['ParkingSpaceIDNum'];
                            $is_property_insurance->PavedRoad = $listing['PavedRoad'];
                            $is_property_insurance->PendingDate = (isset($listing['PendingDate']) && $listing['PendingDate'] == '') ? null : $listing['PendingDate'];
                            $is_property_insurance->PermittedPropertyManager = isset($listing['PermittedPropertyManager']) && $listing['PermittedPropertyManager'] != '' ? $listing['PermittedPropertyManager'] : 0;
                            $is_property_insurance->PerPetYN = isset($listing['PerPetYN']) && $listing['PerPetYN'] != '' ? $listing['PerPetYN'] : 0;
                            $is_property_insurance->PetDeposit = $listing['PetDeposit'];
                            $is_property_insurance->PetDescription = $listing['PetDescription'];
                            $is_property_insurance->PetRefund = $listing['PetRefund'];
                            $is_property_insurance->PetsAllowed = isset($listing['PetsAllowed']) && $listing['PetsAllowed'] != '' ? $listing['PetsAllowed'] : 0;
                            $is_property_insurance->PhotoExcluded = isset($listing['PhotoExcluded']) && $listing['PhotoExcluded'] != '' ? $listing['PhotoExcluded'] : 0;
                            $is_property_insurance->PhotoInstructions = $listing['PhotoInstructions'];
                            $is_property_insurance->PhotoModificationTimestamp = (isset($listing['PhotoModificationTimestamp']) && $listing['PhotoModificationTimestamp'] == '') ? null : $listing['PhotoModificationTimestamp'];
                            $is_property_insurance->PoolLength = $listing['PoolLength'];
                            $is_property_insurance->PoolWidth = $listing['PoolWidth'];
                            $is_property_insurance->PostalCodePlus4 = $listing['PostalCodePlus4'];
                            $is_property_insurance->PreviousParcelNumber = $listing['PreviousParcelNumber'];
                            $is_property_insurance->PriceChangeTimestamp = (isset($listing['PriceChangeTimestamp']) && $listing['PriceChangeTimestamp'] == '') ? null : $listing['PriceChangeTimestamp'];
                            $is_property_insurance->PriceChgDate = (isset($listing['PriceChgDate']) && $listing['PriceChgDate'] == '') ? null : $listing['PriceChgDate'];
                            $is_property_insurance->PricePerAcre = $listing['PricePerAcre'];
                            $is_property_insurance->PrimaryViewDirection = $listing['PrimaryViewDirection'];
                            $is_property_insurance->ProjAmenitiesDescription = $listing['ProjAmenitiesDescription'];
                            $is_property_insurance->PropAmenitiesDescription = $listing['PropAmenitiesDescription'];
                            $is_property_insurance->PropertyCondition = $listing['PropertyCondition'];
                            $is_property_insurance->PropertyInsurance = $listing['PropertyInsurance'];
                            $is_property_insurance->ProviderKey = $listing['ProviderKey'];
                            $is_property_insurance->ProviderModificationTimestamp = (isset($listing['ProviderModificationTimestamp']) && $listing['ProviderModificationTimestamp'] == '') ? null : $listing['ProviderModificationTimestamp'];
                            $is_property_insurance->save();
                        } catch (\Exception $ex) {
                            Log::info("Error in Property Insurance : " .$ex->getMessage());
                        }
                        
                        // Property Interior Detail
                        try {
                            $is_property_interior_details = PropertyInteriorDetail::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                            if($is_property_interior_details == '') {
                                $is_property_interior_details = new PropertyInteriorDetail();
                                $is_property_interior_details->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                $is_property_interior_details->MLSNumber = $listing['MLSNumber'];
                            }
                            $is_property_interior_details->KeyDeposit = $listing['KeyDeposit'];
                            $is_property_interior_details->KeyRefund = $listing['KeyRefund'];
                            $is_property_interior_details->KitchenCountertops = $listing['KitchenCountertops'];
                            $is_property_interior_details->LandlordOwnerPays = $listing['LandlordOwnerPays'];
                            $is_property_interior_details->LandUse = $listing['LandUse'];
                            $is_property_interior_details->LastChangeTimestamp = (isset($listing['LastChangeTimestamp']) && $listing['LastChangeTimestamp'] == '') ? null : $listing['LastChangeTimestamp'];
                            $is_property_interior_details->LastChangeType = $listing['LastChangeType'];
                            $is_property_interior_details->LastListPrice = $listing['LastListPrice'];
                            $is_property_interior_details->LastStatus = $listing['LastStatus'];
                            $is_property_interior_details->LeaseDescription = $listing['LeaseDescription'];
                            $is_property_interior_details->LeaseOptionConsideredY = isset($listing['LeaseOptionConsideredY']) && $listing['LeaseOptionConsideredY'] ? $listing['LeaseOptionConsideredY'] : 0;
                            $is_property_interior_details->LeasePrice = $listing['LeasePrice'];
                            $is_property_interior_details->LeedCertified = isset($listing['LeedCertified']) && $listing['LeedCertified'] != '' ? $listing['LeedCertified'] : 0;
                            $is_property_interior_details->LegalDescription = $listing['LegalDescription'];
                            $is_property_interior_details->Length = $listing['Length'];
                            $is_property_interior_details->ListAgent_MUI = $listing['ListAgent_MUI'];
                            $is_property_interior_details->ListingContractDate = (isset($listing['ListingContractDate']) && $listing['ListingContractDate'] == '') ? null : $listing['ListingContractDate'];
                            $is_property_interior_details->ListOffice_MUI = $listing['ListOffice_MUI'];
                            $is_property_interior_details->ListOfficeMLSID = $listing['ListOfficeMLSID'];
                            $is_property_interior_details->ListOfficePhone = $listing['ListOfficePhone'];
                            $is_property_interior_details->LitigationType = $listing['LitigationType'];
                            $is_property_interior_details->Location = $listing['Location'];
                            $is_property_interior_details->LotDepth = $listing['LotDepth'];
                            $is_property_interior_details->LotFront = $listing['LotFront'];
                            $is_property_interior_details->LotFrontage = $listing['LotFrontage'];
                            $is_property_interior_details->LotNumber = $listing['LotNumber'];
                            $is_property_interior_details->Maintenance = $listing['Maintenance'];
                            $is_property_interior_details->Management = $listing['Management'];
                            $is_property_interior_details->Manufactured = isset($listing['Manufactured']) && $listing['Manufactured'] != '' ? $listing['Manufactured'] : 0;
                            $is_property_interior_details->MapDescription = $listing['MapDescription'];
                            $is_property_interior_details->MasterBedroomDownYN = isset($listing['MasterBedroomDownYN']) && $listing['MasterBedroomDownYN'] != '' ? $listing['MasterBedroomDownYN'] : 0;
                            $is_property_interior_details->MasterPlan = $listing['MasterPlan'];
                            $is_property_interior_details->MatrixModifiedDT = $listing['MatrixModifiedDT'];
                            $is_property_interior_details->MediaRoomYN = isset($listing['MediaRoomYN']) && $listing['MediaRoomYN'] != '' ? $listing['MediaRoomYN'] : 0;
                            $is_property_interior_details->MetroMapCoorXP = $listing['MetroMapCoorXP'];
                            $is_property_interior_details->MetroMapPageXP = $listing['MetroMapPageXP'];
                            $is_property_interior_details->MHYrBlt = $listing['MHYrBlt'];
                            $is_property_interior_details->MLNumofPropIfforSale = $listing['MLNumofPropIfforSale'];
                            $is_property_interior_details->MLS = $listing['MLS'];
                            $is_property_interior_details->save();
                        } catch (\Exception $ex) {
                            Log::info("Error in Property Interior Detail : " .$ex->getMessage());
                        }
                        
                        // Property Other Information
                        try {
                            $is_property_other_information = PropertyOtherInformation::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                            if($is_property_other_information == '') {
                                $is_property_other_information = new PropertyOtherInformation();
                                $is_property_other_information->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                $is_property_other_information->MLSNumber = $listing['MLSNumber'];
                            }
                            $is_property_other_information->RentTermsDescription = $listing['RentTermsDescription'];
                            $is_property_other_information->Road = $listing['Road'];
                            $is_property_other_information->StateOrProvince = $listing['StateOrProvince'];
                            $is_property_other_information->StatusChangeTimestamp = (isset($listing['StatusChangeTimestamp']) && $listing['StatusChangeTimestamp'] == '') ? null : $listing['StatusChangeTimestamp'];
                            $is_property_other_information->StatusContractualSearchDate = (isset($listing['StatusContractualSearchDate']) && $listing['StatusContractualSearchDate'] == '') ? null : $listing['StatusContractualSearchDate'];
                            $is_property_other_information->StatusUpdate = (isset($listing['StatusUpdate']) && $listing['StatusUpdate'] == '') ? null : $listing['StatusUpdate'];
                            $is_property_other_information->StorageSecure = isset($listing['StorageSecure']) && $listing['StorageSecure'] != '' ? $listing['StorageSecure'] : 0;
                            $is_property_other_information->StorageUnitDesc = $listing['StorageUnitDesc'];
                            $is_property_other_information->StorageUnitDim = $listing['StorageUnitDim'];
                            $is_property_other_information->Table = $listing['Table'];
                            $is_property_misscellaneous->TempOffMarketDate = (isset($listing['TempOffMarketDate']) && $listing['TempOffMarketDate'] == '') ? null : $listing['TempOffMarketDate'];
                            $is_property_other_information->TerraceLocation = $listing['TerraceLocation'];
                            $is_property_other_information->TerraceTotalSqft = $listing['TerraceTotalSqft'];
                            $is_property_other_information->TerrainDescription = $listing['TerrainDescription'];
                            $is_property_other_information->TotalFloors = $listing['TotalFloors'];
                            $is_property_other_information->TotalNumofParkingSpaces = $listing['TotalNumofParkingSpaces'];
                            $is_property_other_information->TowerName = $listing['TowerName'];
                            $is_property_other_information->Town = $listing['Town'];
                            $is_property_other_information->Township = $listing['Township'];
                            $is_property_other_information->TransactionType = $listing['TransactionType'];
                            $is_property_other_information->Trash = $listing['Trash'];
                            $is_property_other_information->TStatusDate = (isset($listing['TStatusDate']) && $listing['TStatusDate'] == '') ? null : $listing['TStatusDate'];
                            $is_property_other_information->TypeOwnerWillCarry = $listing['TypeOwnerWillCarry'];
                            $is_property_other_information->UnitCount = $listing['UnitCount'];
                            $is_property_other_information->UnitDescription = $listing['UnitDescription'];
                            $is_property_other_information->UnitNumber = $listing['UnitNumber'];
                            $is_property_other_information->UnitPoolIndoorYN = isset($listing['UnitPoolIndoorYN']) && $listing['UnitPoolIndoorYN'] != '' ? $listing['UnitPoolIndoorYN'] : 0;
                            $is_property_other_information->UnitSpaIndoor = isset($listing['UnitSpaIndoor']) && $listing['UnitSpaIndoor'] != '' ? $listing['UnitSpaIndoor'] : 0;;
                            $is_property_other_information->Utilities = $listing['Utilities'];
                            $is_property_other_information->UtilitiesIncl = $listing['UtilitiesIncl'];
                            $is_property_other_information->Views = $listing['Views'];
                            $is_property_other_information->Washer = $listing['Washer'];
                            $is_property_other_information->WasherDryerDescription = $listing['WasherDryerDescription'];
                            $is_property_other_information->WasherDryerIncluded = $listing['WasherDryerIncluded'];
                            $is_property_other_information->WaterHeaterDescription = $listing['WaterHeaterDescription'];
                            $is_property_other_information->WeightLimit = $listing['WeightLimit'];
                            $is_property_other_information->Width = $listing['Width'];
                            $is_property_other_information->ZoningAuthority = $listing['ZoningAuthority'];
                            $is_property_other_information->save();
                        } catch (\Exception $ex) {
                            Log::info("Error in Property Other Information : " .$ex->getMessage());
                        }
                        
                        // Property Selling Details
                        try {
                            $is_property_selling_details = PropertySellingDetails::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                            if($is_property_selling_details == '') {
                                $is_property_selling_details = new PropertySellingDetails();
                                $is_property_selling_details->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                                $is_property_selling_details->MLSNumber = $listing['MLSNumber'];
                            }
                            $is_property_selling_details->SaleOfficeBonusYN = isset($listing['SaleOfficeBonusYN']) && $listing['SaleOfficeBonusYN'] != '' ? $listing['SaleOfficeBonusYN'] : 0;
                            $is_property_selling_details->SaleType = $listing['SaleType'];
                            $is_property_selling_details->SecondEncumbranceAssumable = $listing['SecondEncumbranceAssumable'];
                            $is_property_selling_details->SecondEncumbranceBalance = $listing['SecondEncumbranceBalance'];
                            $is_property_selling_details->SecondEncumbrancePayment = $listing['SecondEncumbrancePayment'];
                            $is_property_selling_details->SecondEncumbrancePmtDesc = $listing['SecondEncumbrancePmtDesc'];
                            $is_property_selling_details->SecondEncumbranceRate = $listing['SecondEncumbranceRate'];
                            $is_property_selling_details->Section = $listing['Section'];
                            $is_property_selling_details->Section8ConsideredYN = isset($listing['Section8ConsideredYN']) && $listing['Section8ConsideredYN'] != '' ? $listing['Section8ConsideredYN'] : 0;
                            $is_property_selling_details->Security = $listing['Security'];
                            $is_property_selling_details->SecurityDeposit = $listing['SecurityDeposit'];
                            $is_property_selling_details->SecurityRefund = $listing['SecurityRefund'];
                            $is_property_selling_details->SellerContribution = $listing['SellerContribution'];
                            $is_property_selling_details->SellingAgent_MUI = $listing['SellingAgent_MUI'];
                            $is_property_selling_details->SellingAgentDirectWorkPhone = $listing['SellingAgentDirectWorkPhone'];
                            $is_property_selling_details->SellingAgentFullName = $listing['SellingAgentFullName'];
                            $is_property_selling_details->SellingAgentMLSID = $listing['SellingAgentMLSID'];
                            $is_property_selling_details->SellingOffice_MUI = $listing['SellingOffice_MUI'];
                            $is_property_selling_details->SellingOfficeMLSID = $listing['SellingOfficeMLSID'];
                            $is_property_selling_details->SellingOfficeName = $listing['SellingOfficeName'];
                            $is_property_selling_details->SellingOfficePhone = $listing['SellingOfficePhone'];
                            $is_property_selling_details->SeparateMeter = $listing['SeparateMeter'];
                            $is_property_selling_details->ServiceContractInc = $listing['ServiceContractInc'];
                            $is_property_selling_details->ServicesAvailableOnSite = $listing['ServicesAvailableOnSite'];
                            $is_property_selling_details->ShowingAgentPublicID = $listing['ShowingAgentPublicID'];
                            $is_property_selling_details->SIDLIDAnnualAmount = $listing['SIDLIDAnnualAmount'];
                            $is_property_selling_details->SIDLIDBalance = $listing['SIDLIDBalance'];
                            $is_property_selling_details->SoldAppraisal_NUMBER = $listing['SoldAppraisal_NUMBER'];
                            $is_property_selling_details->SoldBalloonAmt = $listing['SoldBalloonAmt'];
                            $is_property_selling_details->SoldBalloonDue = $listing['SoldBalloonDue'];
                            $is_property_selling_details->SoldDownPayment = $listing['SoldDownPayment'];
                            $is_property_selling_details->SoldLeaseDescription = $listing['SoldLeaseDescription'];
                            $is_property_selling_details->SoldOWCAmt = $listing['SoldOWCAmt'];
                            $is_property_selling_details->SoldTerm = $listing['SoldTerm'];
                            $is_property_selling_details->OffMarketDate = (isset($listing['OffMarketDate']) && $listing['OffMarketDate'] == '') ? null : $listing['OffMarketDate'];
                            $is_property_selling_details->save();
                        } catch (\Exception $ex) {
                            Log::info("Error in Property Selling Details : " .$ex->getMessage());
                        }

                        $is_property_latlong = PropertyLatLong::where('Matrix_Unique_ID',$listing['Matrix_Unique_ID'])->first();
                        if($is_property_latlong == '' && $listing['PublicAddressYN'] == 1 && $listing['PublicAddress'] != ''){
                            try{
                                $formattedAddr = str_replace(' ', '+', $listing['PublicAddress']);
                                $final_address = $formattedAddr . '+' . $listing['PostalCode'];
                                $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $final_address . '&key=AIzaSyCpUfYECkxluIkMJpQtdbFBpxiFn2xEQD8');
                                $output = json_decode($geocodeFromAddr);

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
                                //Return latitude and longitude of the given address
                                if (stripos($data['formatted_address'], $cityArray[$this->city]) !== false) {
                                    if ($is_property_latlong) {
                                        $is_property_latlong->MLSNumber = $listing['MLSNumber'];
                                        $is_property_latlong->latitude = $data['latitude'];
                                        $is_property_latlong->longitude = $data['longitude'];
                                        $is_property_latlong->FormatedAddress = $data['formatted_address'];
                                        $is_property_latlong->save();
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
                                Log::info('ERROR GOOGLE API !! '.$e->getMessage());
                            }
                        }
                        $key++;
                    } catch (\Exception $exception){
                        Log::info('Error !! '.$exception->getMessage());
                    }
                }
                $rets->FreeResult($search);
                $rets->Disconnect();
            }
        } catch (\Exception $e) {
            Log::info('error job !! ' . $e->getMessage());
        }
        Log::info('Your Queue is finish. And total data inserted = '.$rowCount);
    }
}
