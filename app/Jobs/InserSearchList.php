<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use phRETS;
use App\Citylist;
class InserSearchList implements ShouldQueue
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
         $count=0;
            foreach($cityList as $list){
                $query_city="(City={$list->name})";
                $search_query = $rets->SearchQuery("Property","Listing",$query_city,array("StandardNames" => 0));
                $result_count_city=$rets->TotalRecordsFound();

                $update_city=Citylist::find($list->id);
                $update_city->total=$result_count_city;
                $update_city->updated_at=Date('Y-m-d');
                if($update_city->save()){
                    $count++;
                }
            }

            if($count == $cityList->count()){
                foreach($cityList as $list){
                $this->InsertSearchdata($list->name);
                }
            }
        }
    }

    public function InsertSearchdata($city){
            echo $city;
    }
}
