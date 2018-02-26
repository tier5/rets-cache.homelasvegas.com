<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyDetail extends Model
{
    public function propertyfeature()
    {
        return $this->hasOne('App\PropertyFeature', 'Matrix_Unique_ID', 'Matrix_Unique_ID');

    }

    public function propertyadditional()
    {
        return $this->hasOne('App\PropertyAdditional', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }

    public function propertyexternalfeature()
    {
        return $this->hasOne('App\PropertyExternalFeature', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }

    public function propertyfinancialdetail()
    {
        return $this->hasOne('App\PropertyFinancialDetail', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }

    public function propertyimage()
    {
        return $this->hasMany('App\PropertyImage', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }

    public function propertyinteriorfeature()
    {
        return $this->hasOne('App\PropertyInteriorFeature', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }

    public function propertylatlong()
    {
        return $this->hasOne('App\PropertyLatLong', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }

    public function propertylocation()
    {
        return $this->hasOne('App\PropertyLocation', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }

    public function PropertyMiscellaneous()
    {
        return $this->hasOne('App\PropertyMiscellaneous','Matrix_Unique_ID','Matrix_Unique_ID');
    }

    public function PropertyAdditionalFeature()
    {
        return $this->hasOne('App\PropertyAdditionalFeature','Matrix_Unique_ID','Matrix_Unique_ID');
    }

    public function PropertyAdditionalDetail()
    {
        return $this->hasOne('App\PropertyAdditionalDetail','Matrix_Unique_ID','Matrix_Unique_ID');
    }

    public function PropertyInteriorDetail()
    {
        return $this->hasOne('App\PropertyInteriorDetail','Matrix_Unique_ID','Matrix_Unique_ID');
    }

    public function PropertyFinancialAdditional()
    {
        return $this->hasOne('App\PropertyFinancialAdditional','Matrix_Unique_ID','Matrix_Unique_ID');
    }

    public function PropertyOtherInformation()
    {
        return $this->hasOne('App\PropertyOtherInformation','Matrix_Unique_ID','Matrix_Unique_ID');
    }

    public function PropertyInsurance()
    {
        return $this->hasOne('App\PropertyInsurance','Matrix_Unique_ID','Matrix_Unique_ID');
    }

    public function PropertySellingDetails()
    {
        return $this->hasOne('App\PropertySellingDetails','Matrix_Unique_ID','Matrix_Unique_ID');
    }
}
