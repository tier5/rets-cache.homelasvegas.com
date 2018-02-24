<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyMiscellaneousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_miscellaneous', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID')->index()->unique();
            $table->char('MLSNumber',20)->index()->unique();
            $table->string('NetAcres',20)->nullable();
            $table->dateTime('NODDate')->nullable();
            $table->string('NOI',11)->nullable();
            $table->string('NumberofFurnishedUnits',11)->nullable();
            $table->string('NumberofPets',11)->nullable();
            $table->string('NumBldgs',11)->nullable();
            $table->string('NumFloors',11)->nullable();
            $table->string('NumGAcres',20)->nullable();
            $table->string('NumLoft',11)->nullable();
            $table->string('NumofLoftAreas',11)->nullable();
            $table->string('NumofParkingSpacesIncluded',11)->nullable();
            $table->string('NumParcels',11)->nullable();
            $table->string('NumParking',11)->nullable();
            $table->string('NumStorageUnits',11)->nullable();
            $table->string('NumTerraces',11)->nullable();
            $table->char('NumUnits',75)->nullable();
            $table->string('SubjecttoFIRPTAYN',11)->nullable();
            $table->longText('Style')->nullable();
            $table->string('StudioYN',11)->nullable();
            $table->char('StreetSuffix',32)->nullable();
            $table->char('StreetDirSuffix',32)->nullable();
            $table->char('StreetDirPrefix',32)->nullable();
            $table->dateTime('OffMarketDate')->nullable();
            $table->string('OnSiteStaff',11)->nullable();
            $table->string('Range',11)->nullable();
            $table->string('RATIO_ClosePrice_By_ListPrice',20)->nullable();
            $table->string('RATIO_ClosePrice_By_OriginalListPrice',20)->nullable();
            $table->longText('RefrigeratorDescription',1000)->nullable();
            $table->string('RentedPrice',11)->nullable();
            $table->char('RentRange',20)->nullable();
            $table->string('YrsRemaining',11)->nullable();
            $table->string('YearlyOtherIncome',11)->nullable();
            $table->string('YearlyOperatingIncome',11)->nullable();
            $table->string('YearlyOperatingExpense',11)->nullable();
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
        Schema::dropIfExists('property_miscellaneous');
    }
}
