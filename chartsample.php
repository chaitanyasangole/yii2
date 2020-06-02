<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php 
$lastmonth=date('d-M-Y', strtotime('-1 months'));
$nextmonth=date('d-M-Y', strtotime('0 months'));
//var_dump($lastmonth);
//var_dump($currentmonth);
 $form_date=$lastmonth;
    $to_date=$nextmonth;
         if(isset($_POST['fdate'])) 
  {
    $form_date=$_POST['fdate'];
   
  }
  if(isset($_POST['tdate'])) 
  {
    $to_date=$_POST['tdate'];  
  } 
?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class=" row">
    <div class="col-sm-7">
        <h2>Activity Status</h2>
    </div>
    <div class="col-sm-5 ">
         <span>Done </span><span class="dot" style="background-color:green ;"></span>
    <span>In Progress </span><span class="dot" style="background-color: #00ffcc;"></span>
        <span>To Do </span><span class="dot" style="background-color: #ffb3be;"></span>
        <span>Overdue </span><span class="dot" style="background-color: tomato;"></span>
        <span>Information Needed </span><span class="dot" style="background-color:#ffb84d ;"></span>
        <span>Late Days </span><span class="dot" style="background-color:red ;"></span>
        </div>
</div>
<div class="row">
<form id="a" action="" method="post">
    <div class="col-sm-3">
        <label>From Date :</label><input type="date" id="fdate"  value="<?php echo $form_date; ?>" name="fdate" class="fdate" />
    </div>
    <div class="col-sm-3">
        <label>To Date :</label><input type="date" id="tdate" value="<?php echo $to_date; ?>" name="tdate" class="tdate"/>
    </div>
     <div class="col-sm-1">
        <input type="submit" id="btn_submit" class="btn btn-info" value="Go.." style=" font-size:14px;">
    </div>
    <div class="col-sm-3">
        <select class="form-control" id="user_name">
            <option  >Select Member</option>
                    <?php
        		$connection = Yii::$app->getDb();
                        $query_user="SELECT * FROM `user`";
                        $command = $connection->createCommand($query_user);
                        $user = $command->queryAll();
                        foreach ($user as $u) 
                        {
                            $user_id=$u['id'];
                            $firstname=$u['firstname'];
                    ?>
                    <option value="<?php echo $user_id;?>"><?php echo $firstname; ?></option>
                     <?php      
                    }
                    ?>
                    </select>    
  </div>
   
</form>
</div><hr>
<div id="user_page"></div>
<div class="row maindiv">
  <div class="col-sm-2 one" style="width: 220px;height: 570px; border: solid 1px;overflow: scroll; font-size: 18px;text-overflow: ellipsis;margin-left: -15px;">
            <table class="table " style="border:solid 1px" >
                <tr> <th class="sticky" style="border:solid 1px;padding-top: 31px;padding-left: 29px;background-color:#ecf0f5;">Activity Name</th> </tr>
                 <?php   foreach ($data as $d) {?>
                <tr> <td style="padding-left: 0px;"><?php $s_id=$d['id']; ?><input type="button" onclick="$('#<?php echo $s_id; ?>')[0].focus()" value="<?php echo $d['activity_name']; ?>" style="border: hidden;background-color:#ecf0f5;" /></td> <?php  }?> </tr>
            </table>
   </div>
    <div class="maindiv2"></div>
   <div class="col-sm-10 two  maindiv1" style="width:1156px;height: 570px; border: solid 1px;overflow: auto;" >
        <?php                     
                        $date1=date_create($form_date);
                        $date2=date_create($to_date);
                        $diff=date_diff($date1,$date2);
                        $days=$diff->format("%a ");
                        $sdate = date('d-M-Y', strtotime($form_date .' +1 day'));
                        $dd=date('d-M-Y', strtotime($sdate .' -1 day'));
                        $headings = '<table class="sticky" style="margin-bottom: 10px;margin-left: -15px;"><tr>';
                        $s_date=date('Y-m-d', strtotime($dd .' -1 day'));
                    if($days!=0){ 
                    for($i=0;$i<=$days;$i++){
                        $s_date = date('d-M-Y', strtotime($s_date .' +1 day'));
                        $headings = $headings.'<th class="th1"  ><span>'.$s_date.'</span></th>';  
                            }
                        $headings = $headings.'</tr></table><center><div class="loader" id="loader-1"></div></center>';
                        echo $headings;
                    foreach ($data as $d)
                        {
                            $date11=date_create($d['created_date']);
                            $date22=date_create($d['target_date']);
                            $date33=date_create($d['closing_date']);
                            $diff2=date_diff($date22,$date33);
                            $dateC= date_format($date33, 'd-M-Y'); 
                            $end_close_count1=$diff2->format("%a ");
                            if($d['closing_date']==NULL){
                                $end_close_count1=0;
                                $dateC=" ---";
                            }
                            $close_diff_px=($end_close_count1)*80;
                            
                            
                            $diff1=date_diff($date11,$date22);
                            $start_end_count=$diff1->format("%a ");
                            $color_px=($start_end_count)*90;
                            $di=date_diff($date11,$date1);
                            $range=$di->format("%a ");
                            $blank_px=($range)*60;
                            $dateS= date_format($date11, 'd-M-Y');
                            $dateE= date_format($date22, 'd-M-Y');
                            
                           $blank_box=' <div class="container"><div class="divL"  style="width:'.$blank_px.'px;height: 25px;"></div>';
                           $status=$d['status'];
                    switch ($status) {
                            case "In Progress":
                                    $boxcolor='<div  data-toggle="popover"  data-html="true" data-content="Status : '.$d['status'].'<br/>Start Date : '.$dateS.'<br/>Target Date : '.$dateE.'<br/>Closing Date :'.$dateC.' " class="divR show-modal" id='.$d['id'].' tabindex="1" style="width:'.$color_px.'px; border: solid 0px ;height: 25px;background-color: #00ffcc; overflow: hidden;" ><center  style="color:white;">'.$d['activity_name'].'</center></div><div class="" style="width:'.$close_diff_px.'px; border: solid 0px ;height: 25px;background-color: red;" data-toggle="popover"" data-html="true" data-content="'.$end_close_count1.' Days Late" ></div></div>'; break;
                            case "To Do":
                                    $boxcolor='<div data-toggle="popover"  data-html="true" data-content="Status : '.$d['status'].'<br/>Start Date : '.$dateS.'<br/>Target Date : '.$dateE.'<br/>Closing Date :'.$dateC.'" class="divR show-modal" id='.$d['id'].' tabindex="1" style="width:'.$color_px.'px; border: solid 0px ;height: 25px;background-color: #ffb3be; overflow: hidden;" ><center  style="color:white">'.$d['activity_name'].'</center></div><div class="" style="width:'.$close_diff_px.'px; border: solid 0px ;height: 25px;background-color: red;" data-toggle="popover"" data-html="true" data-content="'.$end_close_count1.' Days Late" ></div></div>'; break;
                            case "Overdue":
                                   $boxcolor='<div data-toggle="popover"  data-html="true" data-content="Status : '.$d['status'].'<br/>Start Date : '.$dateS.'<br/>Target Date : '.$dateE.'<br/>Closing Date :'.$dateC.'" class="divR show-modal" id='.$d['id'].' tabindex="1" style="width:'.$color_px.'px; border: solid 0px ;height: 25px;background-color: tomato; overflow: hidden;" ><center  style="color:white">'.$d['activity_name'].'</center></div><div class="" style="width:'.$close_diff_px.'px; border: solid 0px ;height: 25px;background-color: red;" data-toggle="popover"" data-html="true" data-content="'.$end_close_count1.' Days Late" ></div></div>'; break;
                            case "Information Needed":
                                   $boxcolor='<div data-toggle="popover"  data-html="true" data-content="Status : '.$d['status'].'<br/>Start Date : '.$dateS.'<br/>Target Date : '.$dateE.'<br/>Closing Date :'.$dateC.'" class="divR show-modal" id='.$d['id'].' tabindex="1" style="width:'.$color_px.'px; border: solid 0px ;height: 25px;background-color: #ffb84d; overflow: hidden;" ><center  style="color:white">'.$d['activity_name'].'</center></div><div class="" style="width:'.$close_diff_px.'px; border: solid 0px ;height: 25px;background-color: red;" data-toggle="popover"" data-html="true" data-content="'.$end_close_count1.' Days Late" ></div></div>'; break;
                            case "Done":
                                    $boxcolor='<div data-toggle="popover"  data-html="true" data-content="Status : '.$d['status'].'<br/>Start Date : '.$dateS.'<br/>Target Date : '.$dateE.'<br/>Closing Date :'.$dateC.'" class="divR show-modal" id='.$d['id'].' tabindex="1" style="width:'.$color_px.'px; border: solid 0px ;height: 25px;background-color: green; overflow: hidden;" ><center  style="color:white">'.$d['activity_name'].'</center></div><div class="" style="width:'.$close_diff_px.'px; border: solid 0px ;height: 25px;background-color: red;" data-toggle="popover"" data-html="true" data-content="'.$end_close_count1.' Days Late Done" ></div></div>'; break;
                            default:
                                    $boxcolor='<div data-toggle="popover"  data-html="true" data-content="Status : '.$d['status'].'<br/>Start Date : '.$dateS.'<br/>Target Date : '.$dateE.'<br/>Closing Date :'.$dateC.'" class="divR show-modal" id='.$d['id'].' tabindex="1" style="width:'.$color_px.'px; border: solid 0px  ;height: 25px;background-color: #faf7f8; overflow: hidden;" ><center  style="color:white">'.$d['activity_name'].'</center></div><div class="" style="width:'.$close_diff_px.'px; border: solid 0px ;height: 25px;background-color: red; data-toggle="popover"" data-html="true" data-content="'.$end_close_count1.' Days Late"  ></div></div>'; break;
                                    }
                           echo $blank_box.$boxcolor.'<br>';   
                        }   
                    }
        ?>
   </div></div> 

<div id="testmodal" class="modal fade">
    
                <div id="task_edit">
                   
                </div>
            </div>
            
   <script>   
$(document).ready(() => {

    $("#user_name").change(function(){
        var user_name_id=$('#user_name').val();
        var fdate=$("#fdate").val();
       var tdate=$("#tdate").val();
   $.ajax({
      url: 'index.php?r=activities/user_status&id='+user_name_id +'&fdate='+ fdate +'&tdate='+ tdate ,
   async: false, 
   success: function(result){
       $('.maindiv').hide();
       $('#user_page').html(result);
        } });
  });
 $(".divR").click(function() {
    var id= $(this).attr("id");
    //alert(id);
         $.ajax({
      url: 'index.php?r=activities/edit_task&id='+id,
      async: false, 
      success: function(result){
      $('#task_edit').html(result);
        } });
       
   
  });
    
  var show_btn=$('.show-modal');
 show_btn.click(function(){
      $("#testmodal").modal('show');
  });

            
  $(window).on('load', function() { // makes sure the whole site is loaded 
  $('#loader-1').fadeOut(); // will first fade out the loading animation 
  $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
  $('.tow').delay(350).css({'overflow':'visible'});
});
             $('[data-toggle="popover"]').popover({
        placement : 'top',
        trigger : 'hover'
    });
            
  $(".one").scroll(function () {
$(".two").scrollTop($(".one").scrollTop());
$(".two").scrollRight($(".one").scrollRight());
});
$(".two").scroll(function () {
$(".one").scrollTop($(".two").scrollTop());
$(".one").scrollRight($(".two").scrollRight());
});

});
      </script> 
   
<style>
 table.sticky {
  position: -webkit-sticky;
  position: sticky;
  top: 0;
}     
 th.sticky {
  position: -webkit-sticky;
  position: sticky;
  top: 0;
} 
.dot {
  height: 15px;
  width: 15px;
  border-radius: 50%;
  display: inline-block;
}
/* ALL LOADERS */

.loader{
  width: 100px;
  height: 100px;
  border-radius: 100%;
  position: relative;
  margin-right: 80px;
  margin-top: 168px;
}

/* LOADER 1 */

#loader-1:before, #loader-1:after{
  content: "";
  position: absolute;
  top: -10px;
  left: -10px;
  width: 100%;
  height: 100%;
  border-radius: 100%;
  border: 10px solid transparent;
  border-top-color: #3498db;
}

#loader-1:before{
  z-index: 100;
  animation: spin 1s infinite;
}

#loader-1:after{
  border: 10px solid #ccc;
}

  .container {
        display: flex;
        margin-left: -16px;
       
                min-width: max-content;
      }
     
    .box{
    border: 1px solid green;
  overflow: hidden;
  text-overflow: ellipsis; 
  height: 20px;
  background-color:green;
 color: white;
    } 
#diagonal{
    background: black;
    width: 18em;
    height: 1px;
    transform: rotate(-41deg);
}
   
    hr {
    margin-top: 5px;
    margin-bottom: 5px;
    border: 0;
    border-top: 1px solid #afb1b3;
}
th span {
    transform: rotate(-45deg);
    display: inline-block;
    vertical-align: middle;
    position: absolute;
    right: -70px;
    bottom: 29px;
    height: 0;
    padding: 0.75em 0 1.85em;
    width: 100px;
    z-index: 2;
}
th {
    min-width: 60px;
    max-width: 60px;
    position: relative;
    height: 100px;
    border-bottom: solid 1px;
}
.th1:before {
    content:'';
    display: block;
    position: absolute;
    top: 0;
    right: -50px;
    height: 100px;
    width: 60px;
    border: solid 1px #000;
    border-right: none;
    border-top: none;
    transform: skew(-45deg);
    border-bottom: none;
}
 th:before {
    background: #edf4f7;
    color: #FFF;
    z-index: 0;
}
th:last-child:before {
    border-right: solid 1px #000;
}
tr:first-child td {
    border-top: solid 1px #000;
}
  ::-webkit-inner-spin-button { 
  display:none;
}
::-webkit-calendar-picker-indicator { background-color:white}
input[type=date]{
  font-size:18px;
}
::-webkit-datetime-edit-text { color:#555555}
::-webkit-datetime-edit-month-field { color:#555555 }
::-webkit-datetime-edit-day-field { color: #555555; }
::-webkit-datetime-edit-year-field { color:#555555; }
::-webkit-calendar-picker-indicator{ 
  background-image: url(http://icons.iconarchive.com/icons/dakirby309/simply-styled/256/Calendar-icon.png);
      background-position:center;
       background-size:20px 20px;
       background-repeat:no-repeat;
      color:rgba(204,204,204,0);
}
label{
     font-size:18px;
}
/* width */
::-webkit-scrollbar {
  width: 5px;
}
/* Handle */
::-webkit-scrollbar-thumb {
  background: gray ; 
  
}
/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
 
}
.Activities_name{
    font-size: 20px;
   
    
}

</style>
