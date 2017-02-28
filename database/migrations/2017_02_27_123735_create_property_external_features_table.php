<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyExternalFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_external_features', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID');
            $table->char('MLSNumber',20);
            $table->text('BuildingDescription');
            $table->char('BuiltDescription',75);
            $table->text('ConstructionDescription');
            $table->Boolean('ConvertedGarageYN');
            $table->text('EquestrianDescription');
            $table->text('Fence');
            $table->text('FenceType');
            $table->integer('Garage');
            $table->text('GarageDescription');
            $table->text('HouseViews');
            $table->text('LandscapeDescription');
            $table->text('LotDescription');
            $table->integer('LotSqft');
            $table->text('ParkingDescription');
            $table->text('PoolDescription');
            $table->Boolean('PvPool');
            $table->text('RoofDescription');
            $table->text('Sewer');
            $table->char('SolarElectric',75);
            $table->char('Type',75);
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
        //
    }
}
