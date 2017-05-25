<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyInteriorFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_interior_features', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID');
            $table->char('MLSNumber',20);
            $table->integer('ApproxTotalLivArea');
            $table->char('BathDownstairsDescription',75);
            $table->Boolean('BathDownYN');
            $table->Boolean('BedroomDownstairsYN');
            $table->integer('BedroomsTotalPossibleNum');
            $table->text('CoolingDescription');
            $table->char('CoolingFuel',75);
            $table->Boolean('DishwasherYN');
            $table->Boolean('DisposalYN');
            $table->Boolean('DryerIncluded');
            $table->char('DryerUtilities',75);
            $table->text('EnergyDescription');
            $table->text('FireplaceDescription');
            $table->text('FireplaceLocation');
            $table->integer('Fireplaces');
            $table->text('FlooringDescription');
            $table->text('FurnishingsDescription');
            $table->text('HeatingDescription');
            $table->text('HeatingFuel');
            $table->text('Interior');
            $table->integer('NumDenOther');
            $table->text('OtherApplianceDescription');
            $table->text('OvenDescription');
            $table->integer('RoomCount');
            $table->integer('ThreeQtrBaths');
            $table->text('UtilityInformation');
            $table->Boolean('WasherIncluded');
            $table->text('WasherDryerLocation');
            $table->text('Water');
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
        Schema::dropIfExists('property_interior_features');
    }
}
