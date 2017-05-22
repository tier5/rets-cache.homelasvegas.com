<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use phRETS;
use App\Citylist;
use \DB;
use App\PropertyDetail;
use App\PropertyImage;
use App\PropertyAdditional;
use App\PropertyExternalFeature;
use App\PropertyFeature;
use App\PropertyFinancialDetail;
use App\PropertyInteriorFeature;
use App\PropertyLocation;
use App\PropertyLatLong;
class InsertImages implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $matrix_unique_id;
    public $mls_number;


    public function __construct($matrix_unique_id,$mls_number)
    {
        //
        $this->matrix_unique_id=$matrix_unique_id;
        $this->mls_number=$mls_number;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //echo "Here";

        Log::info('Image Queue Start');

        $rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
		$rets_username = "neal";
		$rets_password = "glvar";



		$rets = new phRETS;

		$rets->AddHeader("RETS-Version", "RETS/1.7.2");

		$connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);


  		if($connect){

        $photos = $rets->GetObject("Property", "LargePhoto", $this->matrix_unique_id, "*", 0);


        $deleteImage = PropertyImage::where('Matrix_Unique_ID', '=', $this->matrix_unique_id)->delete();
                $contentType = $property_image  ='';
                $content_id = $object_id = $Success = 0;
                foreach ($photos as $photo) {
                  
                    if(isset($photo['Content-ID']) && $photo['Content-ID']!='')
                    {
                        $content_id = $photo['Content-ID'];
                    }

                    if(isset($photo['Object-ID']) && $photo['Object-ID']!='')
                    {
                        $object_id = $photo['Object-ID'];
                    }

                    if(isset($photo['Success']) && $photo['Success']!='')
                    {
                        $Success = $photo['Success'];
                    }

                    if ($photo['Success'] == true && isset($photo['Content-Type']) && $photo['Content-Type']!=''){
                        $contentType = $photo['Content-Type'];
                        $property_image = base64_encode($photo['Data']); 
                        
                    }
                    else
                    {
                       $contentType = '';
                        $property_image = ''; 
                    }



                    if(isset($photo['Content-Description']) && $photo['Content-Description'] != '')
                            {
                                $ContentDescription = $photo['Content-Description'];
                            }
                            else
                            {
                                $ContentDescription = '';
                            }
                   
                            
                             $propertyimage =new PropertyImage();
                             $propertyimage->Matrix_Unique_ID = $this->matrix_unique_id;
                             $propertyimage->MLSNumber = $this->mls_number;
                             $propertyimage->ContentId = $content_id;
                             $propertyimage->ObjectId  = $object_id;
                             $propertyimage->Success   = $Success;
                             $propertyimage->ContentType = $contentType;
                             $propertyimage->Encoded_image = $property_image;
                             $propertyimage->ContentDesc = $ContentDescription;
                             $propertyimage->save();
                       
                     
                    
                 }

            }

      
	}


        
}