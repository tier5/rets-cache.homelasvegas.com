<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyAdditional extends Model
{
    //
    public function propertydetail() {
    return $this->belongsTo('App\PropertyDetail', 'Matrix_Unique_ID','Matrix_Unique_ID');
  }
}
