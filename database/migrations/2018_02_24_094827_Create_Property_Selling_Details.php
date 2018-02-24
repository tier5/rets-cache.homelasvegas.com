<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertySellingDetails extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('property_selling_details', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID')->index()->unique();
            $table->char('MLSNumber',20)->index()->unique();
            $table->char('SoldTerm',75)->nullable();
            $table->string('SoldOWCAmt',11)->nullable();
            $table->longText('SoldLeaseDescription')->nullable();
            $table->string('SoldDownPayment',11)->nullable();
            $table->char('SoldBalloonDue',5)->nullable();
            $table->string('SoldBalloonAmt',11)->nullable();
            $table->string('SoldAppraisal_NUMBER',11)->nullable();
            $table->string('SIDLIDBalance',11)->nullable();
            $table->string('SIDLIDAnnualAmount',11)->nullable();
            $table->char('ShowingAgentPublicID',15)->nullable();
            $table->longText('ServicesAvailableOnSite')->nullable();
            $table->longText('ServiceContractInc')->nullable();
            $table->longText('SeparateMeter')->nullable();
            $table->char('SellingOfficePhone',50)->nullable();
            $table->char('SellingOfficeName',50)->nullable();
            $table->char('SellingOfficeMLSID',25)->nullable();
            $table->longText('SellingOffice_MUI')->nullable();
            $table->char('SellingAgentMLSID',25)->nullable();
            $table->char('SellingAgentFullName',150)->nullable();
            $table->char('SellingAgentDirectWorkPhone',50)->nullable();
            $table->longText('SellingAgent_MUI')->nullable();
            $table->string('SellerContribution',20)->nullable();
            $table->char('SecurityRefund',75)->nullable();
            $table->string('SecurityDeposit',11)->nullable();
            $table->longText('Security')->nullable();
            $table->string('Section8ConsideredYN',11)->nullable();
            $table->string('Section',11)->nullable();
            $table->string('SecondEncumbranceRate',16)->nullable();
            $table->longText('SecondEncumbrancePmtDesc')->nullable();
            $table->string('SecondEncumbrancePayment',11)->nullable();
            $table->string('SecondEncumbranceBalance',11)->nullable();
            $table->char('SecondEncumbranceAssumable',75)->nullable();
            $table->char('SaleType',75)->nullable();
            $table->string('SaleOfficeBonusYN',11)->nullable();
            $table->datetime('OffMarketDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('property_selling_details');
    }
}
