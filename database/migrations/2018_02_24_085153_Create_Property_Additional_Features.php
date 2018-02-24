<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyAdditionalFeatures extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('property_additional_features', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID')->index()->unique();
            $table->char('MLSNumber',20)->index()->unique();
            $table->longText('AccessibilityFeatures',1000)->nullable();
            $table->string('ActiveOpenHouseCount',11)->nullable();
            $table->string('AdditionalPetRentYN',11)->nullable();
            $table->char('AdditionalAUSoldTerms',75)->nullable();
            $table->string('AdministrationDeposit',11)->nullable();
            $table->string('AdministrationFeeYN',11)->nullable();
            $table->char('AdministrationRefund',75)->nullable();
            $table->string('AmtOwnerWillCarry',11)->nullable();
            $table->string('ApplicationFeeAmount',20)->nullable();
            $table->string('ApplicationFeeYN',11)->nullable();
            $table->string('ApproxAddlLivArea',11)->nullable();
            $table->string('AppxSubfeeAmount',11)->nullable();
            $table->char('AppxSubfeePymtTy',75)->nullable();
            $table->string('AssessedImpValue',11)->nullable();
            $table->string('AssessedLandValue',11)->nullable();
            $table->string('AssessmentBalance',11)->nullable();
            $table->char('AssessmentType',75)->nullable();
            $table->string('AssessmentYN',11)->nullable();
            $table->string('AssociationFee2',11)->nullable();
            $table->char('AssociationFee2MQYN',75)->nullable();
            $table->char('AssociationFeeMQYN',75)->nullable();
            $table->string('AssociationFeeYN',11)->nullable();
            $table->char('AssociationPhone',12)->nullable();
            $table->dateTime('AuctionDate')->nullable();
            $table->char('AuctionType',75)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('property_additional_features');
    }
}
