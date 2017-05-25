<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductDetails extends Migration
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
             $table->timestamp('OriginalEntryTimestamp')->nullable();
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
            $table->dropColumn('OriginalEntryTimestamp');
        });
    }
}
