<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyInsurance extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('property_insurance', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID')->index()->unique();
            $table->char('MLSNumber',20)->index()->unique();
            $table->longText('OnSiteStaffIncludes',1000)->nullable();
            $table->string('OriginalListPrice',20)->nullable();
            $table->string('OtherDeposit',11)->nullable();
            $table->longText('OtherEncumbranceDesc',1000)->nullable();
            $table->longText('OtherIncomeDescription',1000)->nullable();
            $table->char('OtherRefund',75)->nullable();
            $table->char('OvenFuel',75)->nullable();
            $table->string('OwnerManaged',11)->nullable();
            $table->char('OwnersName',20)->nullable();
            $table->string('OwnerWillCarry',11)->nullable();
            $table->string('PackageAvailable',11)->nullable();
            $table->char('ParkingLevel',3)->nullable();
            $table->char('ParkingSpaceIDNum',15)->nullable();
            $table->char('PavedRoad',75)->nullable();
            $table->dateTime('PendingDate')->nullable();
            $table->string('PermittedPropertyManager',11)->nullable();
            $table->string('PerPetYN',11)->nullable();
            $table->string('PetDeposit',11)->nullable();
            $table->longText('PetDescription',1000)->nullable();
            $table->char('PetRefund',75)->nullable();
            $table->string('PetsAllowed',11)->nullable();
            $table->string('PhotoExcluded',11)->nullable();
            $table->char('PhotoInstructions',75)->nullable();
            $table->dateTime('PhotoModificationTimestamp')->nullable();
            $table->string('PoolLength',11)->nullable();
            $table->string('PoolWidth',11)->nullable();
            $table->char('PostalCodePlus4',4)->nullable();
            $table->char('PreviousParcelNumber',11)->nullable();
            $table->dateTime('PriceChangeTimestamp')->nullable();
            $table->dateTime('PriceChgDate')->nullable();
            $table->string('PricePerAcre',11)->nullable();
            $table->char('PrimaryViewDirection',75)->nullable();
            $table->longText('ProjAmenitiesDescription',1000)->nullable();
            $table->longText('PropAmenitiesDescription',1000)->nullable();
            $table->longText('PropertyCondition',75)->nullable();
            $table->string('PropertyInsurance',11)->nullable();
            $table->char('ProviderKey',25)->nullable();
            $table->dateTime('ProviderModificationTimestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('property_insurance');
    }
}
