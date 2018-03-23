<?php

namespace App\Http\Controllers;

use App\PropertyImage;
use Illuminate\Http\Request;
use App\Jobs\ImportLVPropertyImages;
use Illuminate\Support\Facades\Bus;


class LvimagesController extends Controller
{
	
    public function index() {

    		$rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
            $rets_username = "neal";
            $rets_password = "glvar";
            $rets = new \phRETS();
            $rets->AddHeader("RETS-Version", "RETS/1.7.2");
            $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
            if ($connect) {
            	
            	$search = $rets->SearchQuery("Property", "Listing", '(City=LASVEGAS)', array("StandardNames" => 0, 'Limit' => 10, 'Offset' => 1));
            	$result_count = $rets->TotalRecordsFound();
            	
            	for ($i=0; $i < $result_count; $i = $i+4000) { 
            		 $job = (new ImportLVPropertyImages(4000,$i));
                                Bus::dispatch($job);
            	}
            $rets->FreeResult($search_query);
            return "completed";
        }
    }
}
