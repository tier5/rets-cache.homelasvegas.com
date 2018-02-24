<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyAdditionalDetail extends Model
{
    protected $table = 'property_additional_details';

    public function propertydetail()
    {
        return $this->belongsTo('App\PropertyDetail', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }
}
