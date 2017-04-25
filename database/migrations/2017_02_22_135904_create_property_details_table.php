<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_details', function (Blueprint $table) {
            $table->increments  ('id');
            $table->bigInteger('Matrix_Unique_ID');
            $table->decimal('ListPrice',16);
            $table->char('Status',32);
            $table->integer('BedroomsTotalPossibleNum');
            $table->decimal('BathsTotal',7);
            $table->integer('BathsHalf');
            $table->integer('BathsFull');
            $table->decimal('NumAcres',16);
            $table->integer('SqFtTotal');
            $table->char('StreetNumber',25);
            $table->char('StreetName',50);
            $table->char('City',32);
            $table->char('PostalCode',32);
            

            // $table->char('Area',75);
            // $table->char('StateOrProvince',2);
            // $table->char('PublicAddress',100);

            // $table->char('PostalCode',10);
            // $table->char('VirtualTourLink',255);
            // $table->decimal('NumGAcres',16);
            // $table->integer('PhotoCount');
            // $table->char('MLSNumber',20);
            // $table->dateTime('OriginalEntryTimestamp');

            // $table->integer('YearBuilt');
            // $table->char('PropertyType',32);
            // $table->char('PropertySubType',32);
            // $table->char('CountyOrParish',32);
            // $table->char('Zoning',1000);
            // $table->char('BuildingDescription',1000);
            // $table->char('BuiltDescription',75);
            // $table->char('ConstructionDescription',1000);
            // $table->char('ConvertedGarageYN',1000);
            // $table->char('EquestrianDescription',1000);
            // $table->char('Fence',1000);
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
        Schema::dropIfExists('property_details');
    }
}
