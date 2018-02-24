<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyInsurance extends Model
{
    protected $table = 'property_insurance';

    public function propertydetail()
    {
        return $this->belongsTo('App\PropertyDetail', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }
}
