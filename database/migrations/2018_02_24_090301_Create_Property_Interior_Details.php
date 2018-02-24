<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyInteriorDetails extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('property_interior_details', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID')->index()->unique();
            $table->char('MLSNumber',20)->index()->unique();
            $table->string('KeyDeposit',11)->nullable();
            $table->char('KeyRefund',75)->nullable();
            $table->char('KitchenCountertops',25)->nullable();
            $table->longText('LandlordOwnerPays',1000)->nullable();
            $table->char('LandUse',75)->nullable();
            $table->dateTime('LastChangeTimestamp')->nullable();
            $table->char('LastChangeType',32)->nullable();
            $table->string('LastListPrice',20)->nullable();
            $table->char('LastStatus',32)->nullable();
            $table->longText('LeaseDescription',1000)->nullable();
            $table->string('LeaseOptionConsideredY',11)->nullable();
            $table->string('LeasePrice',20)->nullable();
            $table->string('LeedCertified',11)->nullable();
            $table->char('LegalDescription',200)->nullable();
            $table->string('Length',11)->nullable();
            $table->longText('ListAgent_MUI')->nullable();
            $table->dateTime('ListingContractDate')->nullable();
            $table->longText('ListOffice_MUI')->nullable();
            $table->char('ListOfficeMLSID',25)->nullable();
            $table->char('ListOfficePhone',50)->nullable();
            $table->char('LitigationType',75)->nullable();
            $table->longText('Location',1000)->nullable();
            $table->string('LotDepth',11)->nullable();
            $table->char('LotFront',75)->nullable();
            $table->string('LotFrontage',11)->nullable();
            $table->char('LotNumber',5)->nullable();
            $table->string('Maintenance',11)->nullable();
            $table->string('Management',11)->nullable();
            $table->string('Manufactured',11)->nullable();
            $table->longText('MapDescription',1000)->nullable();
            $table->string('MasterBedroomDownYN',11)->nullable();
            $table->char('MasterPlan',75)->nullable();
            $table->dateTime('MatrixModifiedDT')->nullable();
            $table->string('MediaRoomYN',11)->nullable();
            $table->char('MetroMapCoorXP',2)->nullable();
            $table->string('MetroMapPageXP',11)->nullable();
            $table->char('MHYrBlt',4)->nullable();
            $table->string('MLNumofPropIfforSale',11)->nullable();
            $table->char('MLS',32)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('property_interior_details');
    }
}
