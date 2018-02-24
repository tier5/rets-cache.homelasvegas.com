<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyOtherInformation extends Model
{
    protected $table = 'property_other_informations';

    public function propertydetail()
    {
        return $this->belongsTo('App\PropertyDetail', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }
}
