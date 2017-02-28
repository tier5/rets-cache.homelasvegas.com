<!DOCTYPE html>
<html>
<head>
  <title>RETS Search</title>
</head>
<body>

<form name='rets_search' class="form-horizontal" action="" method="POST" id="rets_search_form"> 
<input type="hidden" name="offset_val" id="offset_val" value="1" />

<input type="hidden" name="_token" value="{{ csrf_token() }}">
  <div class="form-group">
  <label class="col-md-4 control-label" for="city">City</label>  
  <div class="col-md-4">
  <select class='form-control' name='city' id='city'>
    <option value="">--select city--</option>
    <option value="ALAMO"    <?php if(isset($data['city']) && $data['city']== 'ALAMO'){echo "selected";}?>>Alamo</option>
    <option value="ARMAGOSA" <?php if(isset($data['city']) && $data['city']== 'ARMAGOSA'){echo "selected";}?>>Amargosa</option>
    <option value="BEATTY"   <?php if(isset($data['city']) && $data['city']== 'BEATTY'){echo "selected";}?>>Beatty</option>
    <option value="BLUEDIAM" <?php if(isset($data['city']) && $data['city']== 'BLUEDIAM'){echo "selected";}?>>Blue Diamond</option>
    <option value="BOULDERC" <?php if(isset($data['city']) && $data['city']== 'BOULDERC'){echo "selected";}?>>Boulder City</option>
    <option value="CALIENTE" <?php if(isset($data['city']) && $data['city']== 'CALIENTE'){echo "selected";}?>>Caliente</option>
    <option value="CALNEVAR" <?php if(isset($data['city']) && $data['city']== 'CALNEVAR'){echo "selected";}?>>Cal-Nev-Ari</option>
    <option value="COLDCRK"  <?php if(isset($data['city']) && $data['city']== 'COLDCRK'){echo "selected";}?>>Cold Creek</option>
    <option value="ELY"      <?php if(isset($data['city']) && $data['city']== 'ELY'){echo "selected";}?>>Ely</option>
    <option value="GLENDALE" <?php if(isset($data['city']) && $data['city']== 'GLENDALE'){echo "selected";}?>>Glendale</option>
    <option value="GOODSPRG" <?php if(isset($data['city']) && $data['city']== 'GOODSPRG'){echo "selected";}?>>Goodsprings</option>
    <option value="HENDERSON" <?php if(isset($data['city']) && $data['city']== 'HENDERSON'){echo "selected";}?>>Henderson</option>
    <option value="INDIANSP" <?php if(isset($data['city']) && $data['city']== 'INDIANSP'){echo "selected";}?>>Indian Springs</option>
    <option value="JEAN"     <?php if(isset($data['city']) && $data['city']== 'JEAN'){echo "selected";}?>>Jean</option>
    <option value="LASVEGAS" <?php if(isset($data['city']) && $data['city']== 'LASVEGAS'){echo "selected";}?>>Las Vegas</option>
    <option value="LAUGHLIN" <?php if(isset($data['city']) && $data['city']== 'LAUGHLIN'){echo "selected";}?>>Laughlin</option>
    <option value="LOGANDAL" <?php if(isset($data['city']) && $data['city']== 'LOGANDAL'){echo "selected";}?>>Logandale</option>
    <option value="MCGILL"   <?php if(isset($data['city']) && $data['city']== 'MCGILL'){echo "selected";}?>>Mc Gill</option>
    <option value="MESQUITE" <?php if(isset($data['city']) && $data['city']== 'MESQUITE'){echo "selected";}?>>Mesquite</option>
    <option value="MOAPA"    <?php if(isset($data['city']) && $data['city']== 'MOAPA'){echo "selected";}?>>Moapa</option>
    <option value="MTNSPRG"  <?php if(isset($data['city']) && $data['city']== 'MTNSPRG'){echo "selected";}?>>Mountain Spring</option>
    <option value="NORTHLAS" <?php if(isset($data['city']) && $data['city']== 'NORTHLAS'){echo "selected";}?>>North Las Vegas</option>
    <option value="OTHER"    <?php if(isset($data['city']) && $data['city']== 'OTHER'){echo "selected";}?>>Other</option>
    <option value="OVERTON"  <?php if(isset($data['city']) && $data['city']== 'OVERTON'){echo "selected";}?>>Overton</option>
    <option value="PAHRUMP"  <?php if(isset($data['city']) && $data['city']== 'PAHRUMP'){echo "selected";}?>>Pahrump</option>
    <option value="PALMGRDNS" <?php if(isset($data['city']) && $data['city']== 'PALMGRDNS'){echo "selected";}?>>Palm Gardens</option>
    <option value="PANACA"   <?php if(isset($data['city']) && $data['city']=='PANACA'){echo "selected";}?>>Panaca</option>
    <option value="PIOCHE"   <?php if(isset($data['city']) && $data['city']=='PIOCHE'){echo "selected";}?>>Pioche</option>
    <option value="SANDYVLY" <?php if(isset($data['city']) && $data['city']=='SANDYVLY'){echo "selected";}?>>Sandy Valley</option>
    <option value="SEARCHLT" <?php if(isset($data['city']) && $data['city']=='SEARCHLT'){echo "selected";}?>>Searchlight</option>
    <option value="TONOPAH"  <?php if(isset($data['city']) && $data['city']=='TONOPAH'){echo "selected";}?>>Tonopah</option>
    <option value="URSINE"   <?php if(isset($data['city']) && $data['city']=='URSINE'){echo "selected";}?>>Ursine</option>
  </select>
  </div>
</div>







<div class="form-group">
  <label class="col-md-4 control-label" for="limit">Result Per Page</label>  
  <div class="col-md-4">
  <select class='form-control' name='limit' id='limit'>
    <option value="10" <?php if(isset($data['limit']) && $data['limit']=='10'){echo "selected";} ?>>10</option>
    <!-- <option value="20" <?php //if(isset($data['limit']) && $data['limit']=='20'){echo "selected";} ?>>20</option>
    <option value="50" <?php //if(isset($data['limit']) && $data['limit']=='50'){echo "selected";} ?>>50</option>
    <option value="100" <?php //if(isset($data['limit']) && $data['limit']=='100'){echo "selected";} ?>>100</option> -->
  </select>
  </div>
</div>


<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit_search"> </label>
  <div class="col-md-4">
    <button id="submit_search" name="submit_search" class="btn btn-info" type='submit'>Search</button>
    <a href="{{URL::to('ret_search')}}" class="btn btn-info">Back</a>
  </div>
</div>

</form>
<div class='col-md-8'>
Total Search Result : <?php if(isset($total_records)) {echo $total_records;} ?>
<br>
<!-- Pagination Links -->
 <ul class="pager">
<?php
if(isset($data['offset_val']) && $data['offset_val']>$data['limit'])
{
  $data_set = ($data['offset_val']-$data['limit']);
  ?>
  <!-- <li><a href="{{URL::route('do-search',$data_set)}}" id='offset' data-id="{{$data_set}}" > PREVIOUS </a></li> -->

  <li><a href="" id='offset' data-id="{{$data_set}}" > PREVIOUS </a></li>


<?php
}
if(isset($data['count']) && isset($total_records))
{
 
  //if($data['offset_val']!=$total_records){
  if($data['count'] < $total_records){
    $data_set = ($data['offset_val']+$data['limit']);
  ?>
  <!-- <li><a href="{{URL::route('do-search',$data_set)}}" id='offset' data-id="{{$data_set}}"> NEXT </a></li> -->

<li><a href="" id='offset' data-id="{{$data_set}}"> NEXT </a></li>

<?php
  }
}
?>
</ul>



<div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
      @if(isset($search_result) && count($search_result) > 0)
      @foreach($search_result as $search_value)
        
        <div class="block">
          <div class="block-pic">
            <a href="#">
            <img src="<?php echo 'data:'.$search_value['contentType'].';base64,'.base64_encode($search_value['property_image']);?>" alt="img">
            </a>
           
          </div>
          <h2><?php echo $search_value['StreetNumber'].','.$search_value['StreetName'].','.$search_value['City']; ?></h2>
            <div class="table-responsive block-table">
              <table class="table table-bordered table-striped">
                <tr>
                  <td>
                    <strong>Listing Price:</strong>
                    <?php echo $search_value['ListPrice']; ?>
                  </td>
                  <td>
                    <strong>Status:</strong>
                    <?php echo $search_value['Status']; ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Bedrooms:</strong>
                    <?php echo $search_value['BedroomsTotalPossibleNum']; ?>
                  </td>
                  <td>
                    <strong>Total Baths:</strong>
                    <?php echo $search_value['BathsTotal']; ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Full Baths:</strong>
                    <?php echo $search_value['BathsFull']; ?>
                  </td>
                  <td>
                    <strong>Partial Baths:</strong>
                    <?php echo $search_value['BathsHalf']; ?>
                  </td>
                </tr>
                <tr>
                  <!-- <td>
                    <strong>View Dump Data:</strong>
                    <a href="search_details.php?Matrix_Unique_ID=<?php //echo $search_value['Matrix_Unique_ID'];?>" target="_blank">click to view</a>
                  </td> -->
                  <td>
                    <strong>Square Feet:</strong>
                    <?php echo $search_value['SqFtTotal']; ?>
                  </td>
                </tr>
                
                <tr>
                <?php if($search_value['NumAcres']!=""){ ?>
                  <td>
                    <strong>Acres:</strong>
                    <?php echo $search_value['NumAcres']; ?>
                  </td>
                  <?php } ?>
                  <td>
                    <strong>Listing ID:</strong>
                    <?php echo $search_value['MLSNumber']; ?>
                  </td>
                </tr>

                <tr>
                  <!-- <td colspan="2" align="center"><a class="more-btn" href="#">View More</a></td>  --> 
                </tr>
              </table>
              <hr>
              <!-- <a class="btn btn-default btn-sm" href="photo_gallery.php?Matrix_Unique_ID=<?php //echo $search_value['Matrix_Unique_ID'];?>" target="_blank">Photo Gallery(<?php //echo $search_value['PhotoCount']; ?>)</a>
              <a class="btn btn-default btn-sm" href="<?php //echo $search_value['VirtualTourLink'];?>" target="_blank" >Virtual Tour</a> -->
              
              <!-- <a class="btn btn-default btn-sm">Save Property</a> -->
          </div>  
        </div>
   @endforeach
   @endif
      </div>  
</div>


</form>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    
    <script type="text/javascript">
    $('#submit_search').click(function(){

        var offsetval = $('#offset_val').val();
        var baseUrl = "{{URL::to("do_search") }}" + "/" + offsetval;
        $("#rets_search_form").attr("action",baseUrl);
        $('#rets_search_form').submit();

    });



    $(document).on('click','#offset', function(e){  
        e.preventDefault();
        var id = $(this).data('id');
        var baseUrl = "{{  URL::to("do_search") }}" + "/" + id;
        $("#rets_search_form").attr("action",baseUrl);
        //console.log($( '#rets_search_form' ).serialize() );
      
        $('#rets_search_form').submit();
  });
</script>
</body>
</html>