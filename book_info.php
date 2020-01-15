<?php
$library_staff_role_id='LIBST';
session_start();
include('php/mysqli.php');
$student_name='';
$amount_sanction_by_lib_staff=0;
//*********************************************check if this role has provision to approve 
$query37="Select * from tbl_lib_order where role_id='".$_SESSION['Role_id']."' and status_of_approval='YES' ";
$res37=$connection->query($query37);
// print_r($query37);
if(!$res37)
{
    die($connection->error);
}
//*******************************************************************************************
$can_resubmit=0;
$x=$res37->fetch_object();
// print_r(gettype($x));
if($x->resubmit_provsion=='YES')
{
    $can_resubmit=1;
}

//**********************************************check if resubmit buttton is to be shown */
$is_pending=1;
$query37="Select * from tbl_lib_books where book_id='".$_POST['Book_id']."' ";
$res37=$connection->query($query37);
if(!$res37)
{
    die($connection->error);
}
$book=$res37->fetch_object();
//************************************************** frind the respective book_id and store in $book */
$query37="Select User_name from tbl_user where User_id='".$book->faculty_id."';";
$res37=$connection->query($query37);
if(!$res37)
{
    die($connection->error);
}

$name=$res37->fetch_object();

$query37="Select User_name,User_id from tbl_user where User_id='".$book->user_id."';";
$res37=$connection->query($query37);
if(!$res37)
{
    die($connection->error);
}
$student_id='';
while($temp=$res37->fetch_object())
{
    $student_name=$temp->User_name;
    $student_id=$temp->User_id;

}
//****************************************find faculty name */
$flags=array();
$query37="Select * from tbl_lib_flag where book_id='".$_POST['Book_id']."' order by flag_id";
$res37=$connection->query($query37);
if(!$res37)
{
    die($connection->error);
}
$resubmitflag=0;
while($temp=$res37->fetch_object())
{
    $flags[]=$temp;
    if($temp->role_id==$_SESSION['Role_id'] && $temp->status_of_approval!='PENDING')
    {
        $is_pending=0;
    }
    // print_r(gettype($temp->first_resubmit_id));
    if($temp->first_resubmit_id!=null)
    {
        $resubmitflag=1;
    }
}
//*********************************************checks if prending button to be shown and resubmitted button tob shown */

if($book->bill_id==null)
{
die('NO bill found');
}
// print_r($flags);

$query37="Select * from tbl_lib_bills where bill_id='".$book->bill_id."'";
$res37=$connection->query($query37);
if(!$res37)
{
    die($connection->error);
}
$bill=$res37->fetch_object();

$files=scandir($bill->bill_location);
// print_r($files);
$bill_loc=$bill->bill_location.$files[2];

//*********************************************load bill loction */

$query37="Select * from tbl_lib_faculty_book where book_id='".$book->book_id."' order by(details_added) desc limit 1";
$res37=$connection->query($query37);
if(!$res37)
{
    die($connection->error);
}
$details=$res37->fetch_object();
//***********************************find sub coverage latest details */
$convert_cats=explode('#',$book->borrow_category_id);
$convert_cats=array_filter($convert_cats);
// $convert_cats=explode('#','2#');
// print_r($convert_cats);

//*********************************** convert cats has user shown cats see if borrow id ## is present */
$conversion_amount_sum_array=array();
$lets_borrow=0;
$amt_borrow=0;
if(count($convert_cats)>0){
    //*********************amount needs to be borrowed */
    $lets_borrow=1;
    $All_cats_possible=join(',',$convert_cats);
    $query37="Select y.category_name,x.cat_id_1 from tbl_lib_book_conversion as x,tbl_lib_book_category as y 
     where x.cat_id_1 in ($All_cats_possible) 
    and x.cat_id_2='".$details->category_id."' and y.book_cat_id=x.cat_id_1 
     and x.status_of_activation='ACTIVE' and x.sem_id='".$book->sem_id."' order by x.order_sequence";
    // print_r($query37);
    
     $res37=$connection->query($query37);
    
    if(!$res37)
{
    die($connection->error);
}
//**************************************************************convert cats has allowed cats done using checks */
$convert_cats=array();
while($temp=$res37->fetch_object())
{
    $convert_cats[]=$temp;
}
//0=>category id
//1=>total available balance
// print_r($convert_cats);
// echo 'heyyyyyyyyyyyyyyyyyyyyyy';

// yash($convert_cats);




}


$query37="Select * from tbl_lib_book_category where book_cat_id='".$details->category_id."' and 
status_of_activeness='ACTIVE' and sem_id='".$book->sem_id."' limit 1";
$res37=$connection->query($query37);
if(!$res37)
{
    die($connection->error);
}
$category=$res37->fetch_object();
// yash($category);
//*********************************************Get the category of the book with max amount */


// $query37="Select * from tbl_lib_user_category where user_id='".$book->user_id."' and 
// sem_id='".$book->sem_id."' and book_cat_id='".$category->book_cat_id."'";
$query37="Select x.conversion_done,x.book_cat_id,y.category_name,x.book_id,x.approved_amount from tbl_lib_user_category as x,
tbl_lib_book_category as y where x.user_id='".$book->user_id."' and 
x.sem_id='".$book->sem_id."' and x.book_cat_id=y.book_cat_id and x.sem_id=y.sem_id and y.status_of_activeness='ACTIVE'
 ";
$res37=$connection->query($query37);
if(!$res37)
{
    die($connection->error);
}
$Is_spend=0;
$amount=0;
$history_spend=array();
if(mysqli_num_rows($res37)==0)
{
$Is_spend=1;
}
else{
    while($temp=$res37->fetch_object())
    {
        $history_spend[]=$temp;
        $amount+=$temp->approved_amount;
    }
    // yash($history_spend);
    // yash($amount);

}
//******************************Find the history of the user for this category and store sum in amount */

/* **********************************Excess */
 $excess=$bill->amount-($category->allowed_balance-$amount);
//  yash($excess);
 if($excess>0)
 {
    //  yash($convert_cats);
    foreach($convert_cats as $c)
    {
        // echo $c."<br>";
        $query40="Select y.allowed_balance,
        (y.allowed_balance-COALESCE(sum(x.approved_amount),0)) as final
        from tbl_lib_user_category as x,
        tbl_lib_book_category as y where
        x.user_id='".$book->user_id."' and x.book_cat_id='".$c->cat_id_1."' 
         and x.sem_id='".$book->sem_id."' 
         and y.sem_id='".$book->sem_id."' and y.book_cat_id='".$c->cat_id_1."' and y.status_of_activeness='ACTIVE';
         ";
        //  echo $query40;
        $res40=$connection->query($query40);
        if(!$res40){die($connection->error);}
        $temp_array=array();
        $tempz=$res40->fetch_object();
        // yash($tempz);
        if($tempz->final==null){$tempz->final=0;}
        //0=>difference amount
        //1=>total allowed bal
        //2=>cat name
        //3=>amount borrowed
        $conversion_amount_sum_array[$c->category_name][]=$tempz->final;
        $conversion_amount_sum_array[$c->category_name][]=$tempz->allowed_balance;
        $conversion_amount_sum_array[$c->category_name][]=$c->category_name;
        
        $amt_borrow+=$tempz->final;
        $excess=$excess-$tempz->final;
        // yash($amt_borrow);
        if($excess>=0)
        $conversion_amount_sum_array[$c->category_name][]=$tempz->final;
        else
        {
            $conversion_amount_sum_array[$c->category_name][]=$excess+$tempz->final;
        }
        $conversion_amount_sum_array[$c->category_name][]=$c->cat_id_1;
        if($excess<=0){$excess=0;break;}
    
        
        // echo 'hey';print_r($amt_borrow);
        // $conversion_amount_sum_array[]=get_amount_for($c->cat_id_1,$book->user_id,$book->sem_id,$book->book_id);
    }
 }
// yash('history');
// yash($history_spend);

$conversion_record=array();
// yash($category);   

/***************************************** */
// if($is_pending==0)
// {
    $flag=0;
    
    foreach($history_spend as $h)
    {
        if($h->conversion_done=='YES')
        {
            $flag=1;
        break;
        }
    }
    if($flag==1)
    {
        // $query41="Select * from tbl_lib_book_conversion_record where book_id='".$book->book_id."' ";
        $query41="Select * from tbl_lib_book_conversion_record where user_id='".$book->user_id."' ";
        $res41=$connection->query($query41);
        if(!$res41){echo 'Not found';}
        else{
            $conversion_cats=array();
            $tempx=array();
        while($temp=$res41->fetch_object())
        {
            $conversion_cats[]=$temp->conversion_id;
            $tempx[]=$temp;
        }
        
        // yash($tempp);
        if(count($conversion_cats)>0){
            $tempp=join(',',$conversion_cats);
        $query41="select y1.category_name as c1 ,y2.category_name as c2 from tbl_lib_book_category as y1, 
        tbl_lib_book_category as y2,tbl_lib_book_conversion as z where z.conversion_id in ($tempp) and
        y1.book_cat_id=z.cat_id_1 and y2.book_cat_id=z.cat_id_2
        " ;
        // yash($query41);
        $res41=$connection->query($query41);
        if(!$res41)die($connection->error);
        $i=0;
        while($temp=$res41->fetch_object())
        {
            $conversion_record[$i][]=$temp->c1;
            $conversion_record[$i][]=$temp->c2;
            $conversion_record[$i][]=$tempx[$i]->amount_borrowed;
            $i++;
        }
        // yash($conversion_cats);
        $tempx=array();
            
        }
        
        // yash($conversion_record);

        }
        
        
        
    }
// }






/*********************************************** */








// echo $amount;
// print_r($history_spend);

//****************************check basic egilibilty of amount without the conversion */
$is_eligible='NOT Eligible';
if($category->allowed_balance-$amount-$bill->amount>=0){$is_eligible='Eligible';}
$query37="Select sem_details from tbl_lib_semester where sem_id='".$book->sem_id."'";
$res37=$connection->query($query37);
if(!$res37)
{
    die($connection->error);
}
$sem_name=$res37->fetch_object();

$resubmit=array();
// if($book->first_resubmit_id==null || is_null($book->first_resubmit_id)){}
// else{
    // '<td><a href="'.$bill_loc.'" target="_blank">'.'View'.'</a></td>'.
    // '<td><a href="'+$bill_loc+'" download><button class="button">Download</button></a><br></td>';
// }

//********************************find the next card for resubmit if resubmitted */
if($resubmitflag==1)
{
    $query37="Select x.title,x.author,x.faculty_id,x.publisher, x.resubmit_timestamp,x.role_id,
    x.Remarks,y.User_name,x.book_id  from tbl_lib_resubmit as x,tbl_user as y
     where x.book_id='".$book->book_id."' and y.User_id=x.faculty_id";
    $res37=$connection->query($query37);
    // print_r($query37);
    while($temp=$res37->fetch_object())
    {
        $resubmit[]=$temp;
        // print_r($temp);
    }
}

function getaddr($x)
{
    $F=scandir($x);
// print_r($files);
$ANS=$x.$F[2];
return $ANS;
}


function yash($p)
{
    echo '<br>*****';
    print_r($p);
    echo '<br>*****';
}
// function get_amount_for($x,$u,$s,$b)
// {
  
//     // return ($res40->fetch_object())->final;

// }
?>



<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="application/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="application/jquery.min.js"></script>
        <script src="application/popper.min.js"></script>
        <script src="application/bootstrap.min.js"></script>
        <!-- <script src="js/logout.js"></script>
        <script src="js/view-libst.js"></script>
        <script src="js/change-sem-dates.js"></script> -->

        <script>


            
        $(document).ready(function(){
            
            var p=<?php echo $is_pending;?>;
            if(p==0)
            {
                $('#Approve').hide();
                $('#Reject').hide();
                $('#Resubmit').hide();
                

            }
            var role='<?php echo $_SESSION['Role_id']; ?>';
        var libst='<?php echo $library_staff_role_id; ?>';
            //alert(role+"  "+libst);
            if(role!=libst)
            {
                $('#Approve').hide();
                $('#Reject').hide();
                $('#Resubmit').hide();
                $('#change_category').hide();

            }
            
        });

      
         
        
       
   

   

        var count=0;
        var count2=0;
            var btn_id;
       function submit()
       {
        // alert("Yes clicked"+btn_id);
        var remark=$('#Remarks').val();
        // alert('remarks'+remark);
        var status;
        switch(btn_id)
        {
            case 'Approve':status='APPROVED';break;
            case 'Reject':status='REJECTED';break;
            case 'Resubmit':status='RESUBMIT';break;
        }
        // alert(status);
        var book_id=<?php echo $book->book_id ?>;
        // alert(book_id);
        var amount=<?php echo $bill->amount; ?>;
       // alert(amount);
        var user=<?php echo $book->user_id ?>;
        var sem=<?php echo $book->sem_id;?>;
        var catid=<?php echo $category->book_cat_id;  ?>;
        var borrow=<?php echo $lets_borrow; ?>;
        var  borrow_details=<?php echo json_encode($conversion_amount_sum_array);?>;
        // var obj=JSON.parse(borrow_details)
        var total=<?php echo max(($category->allowed_balance-$amount),0) ?>;
        console.log(borrow_details);
        $.post('php/record-status.php',{Total_amount:total
            ,Borrow_details:borrow_details,Borrow_flag:borrow,Status:status,Book_id:book_id,Remarks:remark ,
            Student:user, Amount:amount,Sem:sem,Catid:catid}).done(function(data){
            alert(data);
            console.log(data);
            
        });
        
       }
       function now(id)
       {
        btn_id=id;
           alert("approved"+id);
       }

       function Conversion(){

count2++;
if(count2%2==0)
{
    $('#content-conversion').empty();

}
else{

    var x=`<?php
               $counter=count($conversion_record);
               if($counter>0)
               {
                   echo '<div class="table-responsive ">
                   <table class="table table-striped table-bordered" style=" border:2px solid black">
                  <thead> <tr>
                   <th>Categroy 1</th>
                   <th>Category 2</th>
                   <th>Amount Borrowed</th>
                   </tr></thead>';
                   foreach($conversion_record as $h)
                {
                    // if($is_pending==0){
                        echo '<tr>';
                    echo '<td>'.$h[0].'</td>';
                    echo '<td>'.$h[1].'</td>';
                    echo '<td>'.$h[2].'</td>';
                   
                    echo '</tr>';
                    // }
                    
                }
            }
            else{
                echo 'No Conversion records found';
            }

               ?>`;
               $('#content-conversion').empty();
               $('#content-conversion').append(x);

}
            

       }
       function History()
       {
           count++;
        //    console.log(count);
           if(count%2==0)
           {
            $('#content-history').empty();
           // $('#content-history').append(x);
           }
           else{
            var x=`<?php
            $counter=count($history_spend);
            if($counter>0)
            {
                echo '<div class="table-responsive ">
                <table class="table table-striped table-bordered" style=" border:2px solid black">
               <thead> <tr>
                <th>Approved Amount</th>
                <th>Category</th>
                <th>Book_id</th>
                <th>View</th>
                
                
                </tr></thead>';
                    $pp=0;
                foreach($history_spend as $h)
                {
                    // if($is_pending==0){
                        echo '<tr>';
                    echo '<td>'.$h->approved_amount.'</td>';
                    echo '<td>'.$h->category_name.'</td>';
                    echo '<td>'.$h->book_id.'</td>';
                    echo '<td><form method="post" target="_blank" action="book_info.php">';
                    echo '<input type="hidden" name="Book_id" value='.$h->book_id.'/>';
                    echo '<input type="submit" class="btn btn-primary" name="View" value="View"/></form></td>';
                    echo '</tr>';
                    // }
                    
                }
            }
            else{
                echo 'No history found';
            }
            ?>`;
            $('#content-history').empty();
            $('#content-history').append(x);
           }
       
           
       }
       function change_category(){
var x=`<?php
    
    $query51="Select * from tbl_lib_book_category where 
    status_of_activeness='ACTIVE' and sem_id='".$book->sem_id."' ";
    $res51=$connection->query($query51);
    if(!$res51){echo $connection->error;}
    else{
        echo '<br><div class="row"><div class="col-4">
        Choose category</div>';
        echo '<div class="col-4"><select class="categories">';
        while($t=$res51->fetch_object())
        {
            echo '<option value="'.$t->book_cat_id.'">'.$t->category_name.'</option>';
        }
        
        echo '</select></div>';
        echo '<div class="col-4"><button onclick="submit_category()" class="btn btn-primary">Submit</button> ';
    }
    
    ?>`;
    $('#content-category').empty();
    $('#content-category').append(x);
            
       }

       function submit_category()
       {
           var x=$('select.categories').val();
        //    alert(x+'cat');
           var y='<?php echo $category->book_cat_id; ?>';
           var bk='<?php echo $book->book_id;?> ';
           $(document).ready(function(){
            if(x!=y)
           {
            //    alert(x+" "+y);
            var sub='<?php echo $details->subject_coverage;?>';
               $.post('php/change-category.php',{Sub:sub,Book_id:bk,cat_id:x}).done(function(data){
                alert(data);
                // console.log(data);
                // alert('heyyy');
                //location.reload(true);
               });
               
           }


           

           });
           
       }
       $(window).bind('load',function(){
        
       });
        
  
        </script>

        
        </head>
        <body >
        <div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Are you Sure?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
       Remarks <input type="text" name="Remarks" id="Remarks"/>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
      <button type="button" onclick="submit()" class="btn btn-success" data-dismiss="modal">YES</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">NO</button>
      </div>

    </div>
  </div>
</div>
        <div class="container">
        <?php
        // yash($amt_borrow);
        // yash($category->allowed_balance);
        // yash($amount);
        // yash($bill->amount);
        echo '<br><div class="row"><div class="col-4">
        <button onclick="now(this.id)" id="Approve" class="btn btn-success" data-target="#myModal" data-toggle="modal">Approve</button></div>
        <div class="col-4"><button onclick="now(this.id)" id="Reject" class="btn btn-danger" data-target="#myModal" data-toggle="modal">Reject</button></div>';
        if($can_resubmit==1){
        echo '<div class="col-4"><button onclick="now(this.id)" id="Resubmit" class="btn btn-warning" data-target="#myModal" data-toggle="modal">Resubmit</button></div>';
        }
        echo '</div><br>';
        echo '<div class="card" style="border:2px solid"><div class="card-body">';
        echo '<h3 class="card-title">Title: '.$book->title.'</h3><br>';
        if($resubmitflag==1)
        {
            echo '<button class="btn btn-warning"  style="margin-left:0px">RESUBMITTED</button><br><br>';
        }
        if($is_pending==1){
            
            if($amt_borrow+$category->allowed_balance-$amount-$bill->amount>=0 && $excess==0)
            {
                $is_eligible='Eligible';
            }
        if($is_eligible=='Eligible')
        {
            echo '<button class="btn btn-success"  style="margin-left:950px">ELIGIBLE</button><br><br>';
        }
        else{
            echo '<button class="btn btn-danger"  style="margin-left:950px">NOT ELIGIBLE</button><br><br>';
        }
    }
        echo '<p class="card-text"> 
        <br><b><u>Student Name: ' .$student_name.
        '<br>Student ID: ' .$student_id.
        '<br></b></u>Author:'   . $book->author.
        '<br>Book_id:' .$book->book_id.
        '<br>Publisher:' .$book->publisher.
        '<br>Faculty:' .$name->User_name.
        '<br>Subject Coverage:' .$details->subject_coverage.
        '<br>Category:' .$category->category_name.
        '<br>Allowed Balance for This Category:' .$category->allowed_balance.
        '<br>Book_added:' .$book->book_added.'</p><br>
        <b><u>Sem_details:' .$sem_name->sem_details.'</b></u></p><br>';
        echo   '<button id="change_category" onclick="change_category()" class="btn btn-warning">Change Book Category</button><br><div id="content-category"></div>';
        echo '<br><button onclick="History()" class="btn btn-primary">History</button><br><div id="content-history"></div>';
        echo '<br><button onclick="Conversion()" class="btn btn-secondary">Conversion Record</button><br><div id="content-conversion"></div>';
        echo '<br><h4><b>Eligibity Details:</b></h4>';
        echo '<div class="table-responsive ">
        <table class="table table-striped table-bordered" style=" border:2px solid black">
       <thead> <tr>
        <th>Bill Amount</th>
        <th>Avaliable Balance for This Category</th>
        <th>Total Allowed Balance</th>
        <th>Category</th>
        <th>Amount sanctioned by library staff</th>
        <th>Eglibilty Status</th>
        
        </tr></thead>';
        echo '<tr>';
        echo '<td>'.$bill->amount.' </td>';
        echo '<td>'.max(($category->allowed_balance-$amount),0).' </td>';
        echo '<td>'.$category->allowed_balance.' </td>';
        echo '<td>'.$category->category_name.' </td>';
        foreach($history_spend as $h)
        {
            if($h->book_id==$book->book_id)
            {
                $amount_sanction_by_lib_staff+=$h->approved_amount;
            }
        }
        // yash($history_spend);
        if($amount_sanction_by_lib_staff!=0)
        echo '<td>'.$amount_sanction_by_lib_staff.' </td>';
        else echo '<td>'.'Yet to approve'.' </td>';
        if($is_pending==0) echo '<td>'.'----'.' </td>';
        else echo '<td>'.$is_eligible.' </td>';
        echo '</tr>';
        echo '<table></div>';

        echo '<h4><b>Bill Details:</b></h4><br><br>';
        echo '<div class="table-responsive ">
        <table class="table table-striped table-bordered" style=" border:2px solid black">
       <thead> <tr>
        <th>Bill No</th>
        <th>Bill Date</th>
        <th>Amount</th>
        <th>Uploaded</th>
        <th>View</th> <th>Download</th>
        </tr></thead>';
        echo '<tr>';
        echo '<td>'.$bill->bill_no.'</td><td>'.$bill->bill_date.'</td><td>'.$bill->amount.
        '</td><td>'.$bill->upload_timestamp.'</td>';
        echo '<td><a href="'.$bill_loc.'" target="_blank">'.'View'.'</a></td>'.
         '<td><a href="'.$bill_loc.'" download><button class="button">Download</button></a><br></td>';
       
        echo '</tr>';

        echo '</table></div>';

        if($lets_borrow==1 && count($conversion_amount_sum_array)>0)
        {
            echo '<h4>Borrow details</h4><br>';
            echo '<div class="table-responsive ">
        <table class="table table-striped table-bordered" style=" border:2px solid black">';
        if($is_pending==1){
        echo '<thead> <tr>
        <th>Category_Name</th>
        <th>Available Balance</th>
        <th>Max Amount for the category</th>
        <th>Balance To be used from available</th>
        <th>Excess Amount left left after using the balance</th>
        
        </tr></thead>';
        }
        else{
            echo '<thead> <tr>
        <th>Category_Name</th>
        <th>Amount Borrowed</th>
        <th>Max Amount for the category</th>';
        
        }
        
        // $excess=$bill->amount-$category->allowed_balance-$amount;
        // yash($conversion_amount_sum_array);
        if($is_pending==1){
            $excess=$bill->amount-($category->allowed_balance-$amount);;
            foreach($conversion_amount_sum_array as $c)
            {
                echo '<tr>';
                echo '<td>'.$c[2].'</td>';
                echo '<td>'.$c[0].'</td>';
                echo '<td>'.$c[1].'</td>';
                echo '<td>'.$c[3].'</td>';
               $excess-=$c[3];
                echo '<td>'.$excess.'</td>';
                echo '</tr>';
            }
            echo '</table></div>';


        }
        else{
            // yash($history_spend);
            // yash(gettype($history_spend));
            $sum=0;
            $sum_max=0;
            foreach($history_spend as $c)
            {
                // yash(gettype($c));
                if($c->book_id==$book->book_id)
                {
                    $query37="select category_name,allowed_balance from 
                    tbl_lib_book_category where book_cat_id='".$c->book_cat_id."' ";
                    $res37=$connection->query($query37);
                    $tt;
                    $aa;
                    if(!$res37){
                        $tt=$c->book_cat_id;
                        $aa='Unable to fetch';
                    }else{
                        $tt=$res37->fetch_object();
                        $aa=$tt->allowed_balance;
                        $tt=$tt->category_name;
                        
                    }
                    
                    echo '<tr>';
                
                echo '<td>'.$tt.'</td>';
                echo '<td>'.$c->approved_amount.'</td>';
                echo '<td>'.$aa.'</td>';
                echo '</tr>';
                $sum+=$c->approved_amount;
                $sum_max+=$aa;
                }

            }
            echo '<tr class="table-success">';
            echo '<td>'.'Total Amount'.'</td>';
            echo '<td>'.$sum.'</td>';
            echo '<td>'.$sum_max.'</td>';
            echo '</tr>';

            echo '</table></div>';

        }

        }
        echo '<br><br><h4>Approval Details</h4><br>';

        echo '<div class="table-responsive">
        <table class="table table-striped table-bordered" style=" border:2px solid black">
        <thead> <tr>
        <th>Staff</th>
        <th>Status Of Approval</th>
        <th>Remarks</th>
        <th>Status recorded at</th>
        </tr></thead>';
        echo '<tr>';
        foreach($flags as $f)
        {
            echo '<tr>';
            echo '<td>'.$f->role_id.'</td>';
            echo '<td>'.$f->status_of_approval.'</td>';
            if($f->Remarks==null)
            {
                $f->Remarks='NONE';
            }
            echo '<td>'.$f->Remarks.'</td>';
            echo '<td>'.$f->flag_timestamp.'</td>';
            echo '</tr>';
        }
        
       
        echo '</tr>';

        echo '</table></div></div></div><br>';

        if($resubmitflag==1){
            // print_r($resubmit);

            if(count($resubmit)>1){

                echo '<h3> Resubmits:</h3>';
                echo '<div class="card" style="border:2px solid"><div class="card-body">';
                echo '<p class="card-text">';
                echo '<h5><b>Resubmits For book details:</b></h5>';
                echo '<div class="table-responsive table-bordered">
                <table class="table table-striped" style=" border:2px solid black">
               <thead> <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Faculty</th>
                <th>Role_id of Issuer</th> 
                <th>Remarks</th>
                <th>Timestamp</th>
                </tr></thead>';
    
                foreach($resubmit as $r)
                {
                    
            // echo '<h3 class="card-title">Title: '.$r->title.'</h3><br>';
            echo '<tr>';
            echo '<td>'.$r->title.'</td>';
            echo '<td>'.$r->author.'</td>';
            echo '<td>'.$r->publisher.'</td>';
            echo '<td>'.$r->User_name.'</td>';
            echo '<td>'.$r->role_id.'</td>';
            echo '<td>'.$r->Remarks.'</td>';
            echo '<td>'.$r->resubmit_timestamp.'</td>';
            
            echo '</tr>';
                }
                echo '</table></div></div>';
    
            }
            
            

        
        $query37="Select x.book_id,x.faculty_id,x.subject_coverage,x.category_id,z.User_name,
        x.details_added,y.category_name from tbl_lib_faculty_book as x,tbl_lib_book_category as y,
        tbl_user as z where book_id='".$book->book_id."'
         and y.book_cat_id=x.category_id and x.faculty_id=z.User_id ";
        //  print_r($query37);
        $res37=$connection->query($query37);
        if(!$res37)
        {
            die($connection->error);
        }
        if(mysqli_num_rows($res37)>0)
        {
            echo '<h5><b>Resubmits For Subject details:</b></h5>';
            echo '<div class="table-responsive table-bordered">
            <table class="table table-striped" style=" border:2px solid black">
           <thead> <tr>
            <th>Faculty_id</th>
            <th>Faculty Name</th>
            <th>Subject Coverage</th>
            <th>Category</th>
            
            <th>Timestamp</th>
            </tr></thead>';
            $sub_coverage=array();
            while($temp=$res37->fetch_object())
            {
                $sub_coverage[]=$temp;
            }
            foreach($sub_coverage as $s)
            {
                echo '<tr>';
                echo '<td>'.$s->faculty_id.'</td>';
                echo '<td>'.$s->User_name.'</td>';
                echo '<td>'.$s->subject_coverage.'</td>';
                echo '<td>'.$s->category_name.'</td>';
                echo '<td>'.$s->details_added.'</td>';
                echo '</tr>';
            }
            echo '</table></div>';

        }
        $query37="Select * from tbl_lib_resubmit_bills where book_id='".$book->book_id."'";
        $res37=$connection->query($query37);
        if(!$res37)
        {
            die($connection->error);
        }
        if(mysqli_num_rows($res37)>0)
        {
            echo '<h5><b>Resubmits For Bill details:</b></h5>';
            echo '<div class="table-responsive table-bordered">
            <table class="table table-striped" style=" border:2px solid black">
           <thead> <tr>
            <th>Bill No</th>
            <th>Bill Date</th>
            <th>Amount</th>
            <th>Uploaded</th>
            <th>View</th> <th>Download</th>
            </tr></thead>';
            $resubmit_bills=array();
            while($temp=$res37->fetch_object())
            {
                $resubmit_bills[]=$temp->bill_id;
            }
            $resubmit_bills=join(',',$resubmit_bills);
            $query37="Select * from tbl_lib_bills where bill_id in ($resubmit_bills) ";
        $res37=$connection->query($query37);
        if(!$res37)
        {
            die($connection->error);
        }
        $resubmit_bills=array();
        while($temp=$res37->fetch_object())
        {
            $temp->bill_location=getaddr($temp->bill_location);
            $resubmit_bills[]=$temp;

        }
        foreach($resubmit_bills as $p)
        {
            echo '<tr>';
            echo '<td>'.$p->bill_no.'</td>';
            echo '<td>'.$p->bill_date.'</td>';
            echo '<td>'.$p->amount.'</td>';
            echo '<td>'.$p->upload_timestamp.'</td>';
            echo '<td><a href="'.$p->bill_location.'" target="_blank">'.'View'.'</a></td>'.
            '<td><a href="'.$p->bill_location.'" download><button class="button">Download</button></a><br></td>';
            echo '</tr>';
        }
        echo '</table></div>';

            
        }
    }
        

         ?>

         

        </div>

</body>
</html>

<?php
$connection->close();
?>