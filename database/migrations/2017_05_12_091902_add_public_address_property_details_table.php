<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublicAddressPropertyDetailsTable extends Migration
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
            $table->text('PublicAddress')->after('PostalCode');
            });
             
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_details', function (Blueprint $table) {
            $table->dropColumn('PublicAddress');
            });
    }
}
