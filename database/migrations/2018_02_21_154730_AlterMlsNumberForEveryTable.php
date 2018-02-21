<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMlsNumberForEveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('property_details', function (Blueprint $table) {
          $table->char('MLSNumber',20)->index()->unique()->change();
          $table->timestamp('updated_at')->index()->change();
      });
      Schema::table('property_additionals', function (Blueprint $table) {
          $table->char('MLSNumber',20)->index()->unique()->change();
          $table->char('PublicAddressYN',20)->index()->change();
      });
      Schema::table('property_external_features', function (Blueprint $table) {
          $table->char('MLSNumber',20)->index()->unique()->change();
      });
      Schema::table('property_features', function (Blueprint $table) {
          $table->char('MLSNumber',20)->index()->unique()->change();
      });
      Schema::table('property_financial_details', function (Blueprint $table) {
          $table->char('MLSNumber',20)->index()->unique()->change();
      });
      Schema::table('property_interior_features', function (Blueprint $table) {
          $table->char('MLSNumber',20)->index()->unique()->change();
      });
      Schema::table('property_lat_longs', function (Blueprint $table) {
          $table->char('MLSNumber',20)->index()->unique()->change();
      });
      Schema::table('property_locations', function (Blueprint $table) {
          $table->char('MLSNumber',20)->index()->unique()->change();
      });
      Schema::table('property_images', function (Blueprint $table) {
        $table->char('MLSNumber',20)->index()->change();
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
