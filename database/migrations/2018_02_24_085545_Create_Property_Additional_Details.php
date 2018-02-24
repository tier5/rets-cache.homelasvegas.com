<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyAdditionalDetails extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('property_additional_details', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID')->index()->unique();
            $table->char('MLSNumber',20)->index()->unique();
            $table->string('BedandBathDownYN',11)->nullable();
            $table->string('BedsTotal',11)->nullable();
            $table->char('BlockNumber',5)->nullable();
            $table->string('BonusSOYN',11)->nullable();
            $table->char('BrandedVirtualTour',255)->nullable();
            $table->char('BuildingNumber',3)->nullable();
            $table->char('BuyerPremium',3)->nullable();
            $table->string('CableAvailable',11)->nullable();
            $table->string('CapRate',20)->nullable();
            $table->char('CarportDescription',75)->nullable();
            $table->string('Carports',11)->nullable();
            $table->string('CashtoAssume',11)->nullable();
            $table->string('CleaningDeposit',11)->nullable();
            $table->char('CleaningRefund',75)->nullable();
            $table->dateTime('CloseDate')->nullable();
            $table->string('ClosePrice', 20)->nullable();
            $table->string('CompactorYN',11)->nullable();
            $table->dateTime('ConditionalDate')->nullable();
            $table->string('CondoConversionYN',11)->nullable();
            $table->string('ConstructionEstimateEnd',11)->nullable();
            $table->dateTime('ConstructionEstimateStart')->nullable();
            $table->char('ContingencyDesc',75)->nullable();
            $table->string('ConvertedtoRealProperty',11)->nullable();
            $table->string('CostperUnit',11)->nullable();
            $table->char('CrossStreet',20)->nullable();
            $table->string('CurrentLoanAssumable',11)->nullable();
            $table->dateTime('DateAvailable')->nullable();
            $table->longText('Deposit',1000)->nullable();
            $table->longText('Directions',500)->nullable();
            $table->longText('DishwasherDescription',1000)->nullable();
            $table->string('DOM',11)->nullable();
            $table->dateTime('DomModifier_DateTime')->nullable();
            $table->string('DomModifier_Initial',11)->nullable();
            $table->char('DomModifier_StatusRValue',25)->nullable();
            $table->string('DownPayment',11)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('property_additional_details');
    }
}
