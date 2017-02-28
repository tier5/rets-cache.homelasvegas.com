<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyFinancialDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_financial_details', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID');
            $table->char('MLSNumber',20);
            $table->integer('AnnualPropertyTaxes');
            $table->integer('AppxAssociationFee');
            $table->integer('AssociationFee1');
            $table->char('AssociationFee1MQYN',75);
            $table->boolean('AVMYN');
            $table->decimal('CurrentPrice',16,2);
            $table->integer('EarnestDeposit');
            $table->text('FinancingConsidered');
            $table->boolean('ForeclosureCommencedYN');
            $table->integer('MasterPlanFeeAmount');
            $table->decimal('RATIO_CurrentPrice_By_SQFT',15,2);
            $table->boolean('RepoReoYN');
            $table->boolean('ShortSale');
            $table->boolean('SIDLIDYN');
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
        //
    }
}
