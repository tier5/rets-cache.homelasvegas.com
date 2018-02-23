<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyMiscellaneous extends Model
{
    protected $table = 'property_miscellaneous';

    public function propertydetail()
    {
        return $this->belongsTo('App\PropertyDetail', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }
}
