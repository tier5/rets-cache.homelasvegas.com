<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyOtherInformtions extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('property_other_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID')->index()->unique();
            $table->char('MLSNumber',20)->index()->unique();
            $table->longText('RentTermsDescription',1000)->nullable();
            $table->longText('Road',1000)->nullable();
            $table->char('StorageUnitDim',5)->nullable();
            $table->char('StorageUnitDesc',75)->nullable();
            $table->boolean('StorageSecure',1)->nullable();
            $table->char('StatusUpdate',75)->nullable();
            $table->dateTime('StatusContractualSearchDate')->nullable();
            $table->dateTime('StatusChangeTimestamp')->nullable();
            $table->char('StateOrProvince',15)->nullable();
            $table->char('ZoningAuthority',75)->nullable();
            $table->string('Width',11)->nullable();
            $table->string('WeightLimit',11)->nullable();
            $table->longText('WaterHeaterDescription')->nullable();
            $table->char('WasherDryerIncluded',75)->nullable();
            $table->char('WasherDryerDescription',75)->nullable();
            $table->char('Washer',75)->nullable();
            $table->longText('Views')->nullable();
            $table->longText('UtilitiesIncl')->nullable();
            $table->string('Utilities',11)->nullable();
            $table->boolean('UnitSpaIndoor',1)->nullable();
            $table->boolean('UnitPoolIndoorYN',1)->nullable();
            $table->char('UnitNumber',25)->nullable();
            $table->longText('UnitDescription')->nullable();
            $table->string('UnitCount',11)->nullable();
            $table->longText('TypeOwnerWillCarry')->nullable();
            $table->dateTime('TStatusDate')->nullable();
            $table->string('Trash',11)->nullable();
            $table->char('TransactionType',32)->nullable();
            $table->string('Township',11)->nullable();
            $table->char('Town',75)->nullable();
            $table->char('TowerName',75)->nullable();
            $table->string('TotalNumofParkingSpaces',20)->nullable();
            $table->char('TotalFloors',3)->nullable();
            $table->longText('TerrainDescription')->nullable();
            $table->string('TerraceTotalSqft',11)->nullable();
            $table->longText('TerraceLocation')->nullable();
            $table->dateTime('TempOffMarketDate')->nullable();
            $table->char('Table',75)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('property_other_informations');
    }
}
