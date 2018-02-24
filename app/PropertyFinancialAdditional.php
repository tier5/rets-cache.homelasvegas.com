<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyFinancialAdditional extends Model
{
    protected $table = 'property_financial_additionals';

    public function propertydetail()
    {
        return $this->belongsTo('App\PropertyDetail', 'Matrix_Unique_ID', 'Matrix_Unique_ID');
    }
}
