<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPropertyLatLong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('property_lat_longs', function (Blueprint $table) {
            //
             
             $table->text('FormatedAddress')->after('longitude');


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
        Schema::table('property_lat_longs', function (Blueprint $table) {
            //
            $table->dropColumn('FormatedAddress');
            
        });
    }
}
