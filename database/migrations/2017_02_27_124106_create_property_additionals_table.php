<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_additionals', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID');
            $table->char('MLSNumber',20);
            $table->Boolean('AgeRestrictedCommunityYN');
            $table->integer('Assessments');
            $table->text('AssociationFeaturesAvailable');
            $table->text('AssociationFeeIncludes');
            $table->char('AssociationName',20);
            $table->char('Builder',10);
            $table->char('CensusTract',10);
            $table->Boolean('CourtApproval');
            $table->Boolean('GatedYN');
            $table->Boolean('GreenBuildingCertificationYN');
            $table->integer('BathsHalf');
            $table->char('ListingAgreementType',75);
            $table->char('Litigation',75);
            $table->char('MasterPlanFeeMQYN',75);
            $table->text('MiscellaneousDescription');
            $table->char('Model',10);
            $table->char('OwnerLicensee',75);
            $table->char('Ownership',75);
            $table->char('PoweronorOff',75);
            $table->text('PropertyDescription');
            $table->char('PropertySubType',32);
            $table->char('PublicAddress',100);
            $table->Boolean('RealtorYN');
            $table->Boolean('RefrigeratorYN');
            $table->char('Spa',75);
            $table->text('SpaDescription');
            $table->Boolean('YearRoundSchoolYN');
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
        Schema::dropIfExists('property_additionals');
    }
}
