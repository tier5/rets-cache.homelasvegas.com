<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertySellingDetails extends Model {
    protected $table = 'property_selling_details';

    public function propertydetail() {
        return $this->belongsTo('App\PropertyDetail', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }
}
