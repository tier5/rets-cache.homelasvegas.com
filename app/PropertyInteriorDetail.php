<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyInteriorDetail extends Model
{
    protected $table = 'property_interior_details';

    public function propertydetail()
    {
        return $this->belongsTo('App\PropertyDetail', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }
}
