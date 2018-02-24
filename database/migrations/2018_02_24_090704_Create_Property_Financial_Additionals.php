<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyFinancialAdditionals extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('property_financial_additionals', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID')->index()->unique();
            $table->char('MLSNumber',20)->index()->unique();
            $table->longText('Electricity',1000)->nullable();
            $table->string('ElevatorFloorNum',11)->nullable();
            $table->string('EnvironmentSurvey',11)->nullable();
            $table->dateTime('EstCloLsedt')->nullable();
            $table->string('ExistingRent',11)->nullable();
            $table->longText('ExpenseSource',1000)->nullable();
            $table->longText('ExteriorDescription',1000)->nullable();
            $table->string('Fireplace',11)->nullable();
            $table->char('FirstEncumbranceAssumable',75)->nullable();
            $table->string('FirstEncumbranceBalance',11)->nullable();
            $table->string('FirstEncumbrancePayment',11)->nullable();
            $table->longText('FirstEncumbrancePmtDesc',1000)->nullable();
            $table->string('FirstEncumbranceRate',20)->nullable();
            $table->char('FloodZone',75)->nullable();
            $table->string('FurnishedYN',11)->nullable();
            $table->longText('GasDescription',1000)->nullable();
            $table->char('GravelRoad',75)->nullable();
            $table->char('GreenCertificationRating',75)->nullable();
            $table->char('GreenCertifyingBody',75)->nullable();
            $table->longText('GreenFeatures',1000)->nullable();
            $table->string('GreenYearCertified',11)->nullable();
            $table->string('GrossOperatingIncome',11)->nullable();
            $table->string('GrossRentMultiplier', 20)->nullable();
            $table->string('GroundMountedYN',11)->nullable();
            $table->string('HandicapAdapted',11)->nullable();
            $table->string('HiddenFranchiseIDXOptInYN', 11)->nullable();
            $table->char('Highlights',20)->nullable();
            $table->char('HOAMinimumRentalCycle',75)->nullable();
            $table->string('HOAYN',11)->nullable();
            $table->char('HomeownerAssociationName',20)->nullable();
            $table->char('HomeownerAssociationPhoneNo',12)->nullable();
            $table->string('HomeProtectionPlan',11)->nullable();
            $table->char('HotWater',75)->nullable();
            $table->char('IDX',1)->nullable();
            $table->string('IDXOptInYN',11)->nullable();
            $table->string('InternetYN',11)->nullable();
            $table->string('JuniorSuiteunder600sqft',11)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('property_financial_additionals');
    }
}
