<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_features', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID');
            $table->char('MLSNumber',20);
            $table->integer('YearBuilt');
            $table->char('PropertyType',32);
            $table->char('PropertySubType',32);
            $table->char('CountyOrParish',32);
            $table->text('Zoning');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_features');
    }
}
