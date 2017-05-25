<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Matrix_Unique_ID');
            $table->char('MLSNumber',20);
            $table->char('Area',75);
            $table->char('CommunityName',75);
            $table->char('ElementarySchool35',75);
            $table->char('ElementarySchoolK2',75);
            $table->char('HighSchool',75);
            $table->text('HouseFaces');
            $table->char('JrHighSchool',75);
            $table->char('ParcelNumber',50);
            $table->integer('StreetNumberNumeric');
            $table->char('SubdivisionName',30);
            $table->integer('SubdivisionNumber');
            $table->char('SubdivisionNumSearch',5);
            $table->char('TaxDistrict',20);
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
        Schema::dropIfExists('property_locations');
    }
}
