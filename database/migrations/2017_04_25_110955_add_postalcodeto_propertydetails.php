<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostalcodetoPropertydetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('property_details', function (Blueprint $table) {
             $table->char('PostalCode',20)->after('City');
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
         Schema::table('property_details', function (Blueprint $table) {
            //
            $table->dropColumn('PostalCode');
        });
    }
}
