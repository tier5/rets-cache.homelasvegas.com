<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Citylist;
use phRETS;
use \DB;
class Searchresultinsert implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
        $rets_username = "neal";
        $rets_password = "glvar";

        $rets = new phRETS;

        $rets->AddHeader("RETS-Version", "RETS/1.7.2");

        $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
        if($connect){
        $cityList = Citylist::orderBy('id','desc')->get();
            foreach($cityList as $list){
                $query="(City={$list->name})";
                $search = $rets->SearchQuery("Property","Listing",$query,array("StandardNames" => 0));
                $result_count=$rets->TotalRecordsFound();

                $update_city=Citylist::find($list->id);
                $update_city->total=$result_count;
                $update_city->updated_at=Date('Y-m-d');
                $update_city->save();
            }
        }else{
            echo "Connection not able to establish";
        }
    }
}
