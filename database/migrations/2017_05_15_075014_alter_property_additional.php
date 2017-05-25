<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPropertyAdditional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_additionals', function (Blueprint $table) {
            //
             $table->char('PublicAddressYN',20)->after('PublicAddress');
             $table->text('PublicRemarks')->after('PublicAddressYN');
              $table->char('ListAgentMLSID',100)->after('PublicRemarks');
             $table->char('ListAgentFullName',100)->after('ListAgentMLSID');

             $table->char('ListOfficeName',100)->after('ListAgentFullName');
             $table->text('ListAgentDirectWorkPhone')->after('ListOfficeName');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_additionals', function (Blueprint $table) {
            //
            $table->dropColumn('PublicAddressYN');
            $table->dropColumn('PublicRemarks');
            $table->dropColumn('ListAgentMLSID');
            $table->dropColumn('ListAgentFullName');
            $table->dropColumn('ListOfficeName');
            $table->dropColumn('ListAgentDirectWorkPhone');
        });
    }
}
