<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyAdditionalFeature extends Model
{
    protected $table = 'property_additional_features';

    public function propertydetail()
    {
        return $this->belongsTo('App\PropertyDetail', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }
}
