<?php
session_start();
include('php/mysqli.php');

if(isset($_POST['Status']))
{
    $can_resubmit=0;
    $query52="Select resubmit_provsion from tbl_lib_order where role_id='".$_SESSION['Role_id']."' and 
    status_of_approval='YES'";
    $res52=$connection->query($query52);
    if(!$res52)die($connection->error);
    $x=$res52->fetch_object();
    if($x->resubmit_provsion=='YES'){ $can_resubmit=1;}
   
    initial($can_resubmit);
    //**********************************Get the books for this role */
    $query52="Select * from tbl_lib_flag where role_id='".$_SESSION['Role_id']."' and status_of_approval='".$_POST['Status']."' ";
    $res52=$connection->query($query52);
    if(!$res52)die($connection->error);
    $book_ids=array();
    while($temp=$res52->fetch_object())
    {
        $book_ids[]=$temp->book_id;
    }
    
    //********************************************************* */
    
    $book_details=array();
    $not_found=0;
    if(count($book_ids)==0){
        $not_found=1;
      

    }
    else{
    $books_ids_str=join(',',$book_ids);
    $query52="Select * from tbl_lib_books where book_id in ($books_ids_str) order by(user_id)";
    $res52=$connection->query($query52);
    if(!$res52)die($connection->error);
    //0=>Book-id 1=>user id  2=>sem id 3=>bill id
    $users=array();
    $sems=array();
    $bills=array();
    while($temp=$res52->fetch_object())
    {
        $book_details[$temp->book_id][]=$temp->book_id;
        $book_details[$temp->book_id][]=$temp->user_id;
        $book_details[$temp->book_id][]=$temp->sem_id;
        $book_details[$temp->book_id][]=$temp->bill_id;
        $users[]=$temp->user_id;
        $sems[]=$temp->sem_id;
        $bills[]=$temp->bill_id;

    }
    $users=join(',',$users);
    $sems=join(',',$sems);
    $bills=join(',',$bills);
   

    $query52="Select User_id,User_name from tbl_user where User_id in ($users) ";
    $res52=$connection->query($query52);
    if(!$res52)die($connection->error);
    $users=array();
    while($temp=$res52->fetch_object())
    {
        $users[$temp->User_id][]=$temp->User_name;
    }
    
    $query52="Select sem_id,sem_details from tbl_lib_semester where sem_id in ($sems) ";
    $res52=$connection->query($query52);
    if(!$res52)die($connection->error);

    $sems=array();
    while($temp=$res52->fetch_object())
    {
        $sems[$temp->sem_id][]=$temp->sem_details;
    }
    
   
    $query52="Select bill_id,amount from tbl_lib_bills where bill_id in ($bills) ";
    $res52=$connection->query($query52);
    if(!$res52)die($connection->error);
    $bills=array();
    while($temp=$res52->fetch_object())
    {
        $bills[$temp->bill_id][]=$temp->amount;
    }
    
$sanctioned=array();
$query52="Select book_id,sum(approved_amount) as final from tbl_lib_user_category where book_id in ($books_ids_str) group by(book_id) ";
$res52=$connection->query($query52);
if(!$res52)die($connection->error);
while($temp=$res52->fetch_object())
{
    $sanctioned[$temp->book_id][]=$temp->final;
}

echo '<div class="table-responsive ">
                   <table class="table table-striped table-bordered" style=" border:2px solid black">
                  <thead> <tr>
                   <th>Sr.No</th>
                   <th>Student Name</th>
                   <th>Student Id</th>
                   <th>Semester</th>
                   <th>Bill Amount</th>
                   <th>Amount Sanctioned by library</th>
                   <th>View More</th>
                   </tr></thead>';
                   $counter=1;
                   //0=>Book-id 1=>user id  2=>sem id 3=>bill id
              
                   $final=array();
                   foreach($book_details as $b){
                $final[$b[1]][0]='';
                $final[$b[1]][1]=0; 
                $final[$b[1]][2]='';
                $final[$b[1]][3]=0; 
                $final[$b[1]][4][]=$b[0];  
                $final[$b[1]][5]=$b[1];  
                $final[$b[1]][6]=$b[2];  
                }
                foreach($book_details as $b){
                    $final[$b[1]][0]=$final[$b[1]][0].strval($sanctioned[$b[0]][0]).'+';
                    $final[$b[1]][1]+=$sanctioned[$b[0]][0];
                    $final[$b[1]][2]=$final[$b[1]][2].strval($bills[$b[3]][0]).'+';
                    $final[$b[1]][3]+=$bills[$b[3]][0];
                }
                $number=count($final);
               foreach($final as &$f)
               {
                $x=strlen($f[0]);
                
                $f[0][$x-1]='=';
                $f[0]=$f[0].strval($f[1]);
                $x=strlen($f[2]);
                $f[2][$x-1]='=';
                $f[2]=$f[2].strval($f[3]);
               }
           
foreach($final as $b){
echo '<tr>';
echo '<td>'.$counter++.'<input name="check"  type="checkbox" id="'.$b[5].'">
</td>';
echo '<td>'.$users[$b[5]][0].'</td>';
echo '<td>'.$b[5].'</td>';
echo '<td>'.$sems[$b[6]][0].'</td>';
echo '<td>'.$b[2].'</td>';
echo '<td>'.$b[0].'</td>';
echo '<td>';
echo '<form target="_blank" action="enlist-books.php" method="post">';
foreach($b[4] as $x)
{
    echo '<input type="hidden" name="Book_id[]" value="'.$x.'">';
}
echo '
<input type="submit" class="btn btn-primary" value="View">
</form></td>';
echo '</tr>';

}

echo '</table></div>';

}
if($not_found==1)
{
    no_books();
}

}

function yash($x)
{
    echo '***********************<br>';
    print_r($x);
    echo '***********************<br>';

}
function initial($can_resubmit)
{
    echo ' <div class="row padding">
    <div class="col-4 " >
    <button  class="btn btn-success" onclick="$.now(this.id)" id="Approve"  data-target="#myModal" data-toggle="modal">Approve</div>
    <div class="col-4 ">
    <button class="btn btn-danger" onclick="$.now(this.id)" id="Reject"  data-target="#myModal" data-toggle="modal">Reject</div>
    ';
    if($can_resubmit==1){echo ' <div class="col-4 ">
        <button class="btn btn-warning" onclick="now(this.id)"
         id="Resubmit"  data-target="#myModal" data-toggle="modal">Resubmit</div>';}
   
    echo '</div><br><br>
    <div class="row padding">
    <div class="col-3 ">
   <input id="all" onchange="$.checkall(this)" type="checkbox" style="margin-right:6px;"/><p>Select All</p><br></div>
   

    </div>';
}

$connection->close();

function no_books()
{
    echo 'NO books found'; 
}

?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="application/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="application/jquery.min.js"></script>
        <script src="application/popper.min.js"></script>
        <script src="application/bootstrap.min.js"></script>
        <script src="js/logout.js"></script>
        <script src="js/view-libst.js"></script>
        <script>
        var btn_id;
$(document).ready(function(){
var notfound='<?php echo $not_found; ?>';
var state='<?php echo $_POST['Status'];?>'
console.log(notfound);
if(state='Approved')
{
    $('#Approve').hide();
    $('#Reject').hide();
    $('#all').hide();
    $('p').hide();

}
if(notfound==="1"){
    
    $('#Approve').hide();
    $('#Reject').hide();
    $('#all').hide();
    $('p').hide();
    $('.modal').hide();
    alert('No books found');

}
    $.now=function(id)
       {
        btn_id=id;
           alert("approved"+id);
       }
$.checkall=function(check)
{
var x=document.getElementsByTagName('input');
var y=false;
if(check.checked)y=true;
for(var i=0;i<x.length;i++)
{
    if(x[i].type=='checkbox')
    {
        x[i].checked=y;
    }
}

}

$.submit=function()
{
    
    var arr=[];
    var check=document.getElementsByTagName('input');
    for(var i=0;i<check.length;i++)
    {
        if(check[i].type=='checkbox' && check[i].name=="check" && check[i].checked )
        {
            arr.push(check[i].id);
        }
    }
    alert(arr);
    

    
  var details=`<?php 
  $val='';
  if(isset($final)){
    $keys=array_keys($final);
    foreach($keys as $k)
    {
        echo $k.'|';
        foreach($final[$k][4] as $t)
        {
            echo $t.',';
        }
        echo '|';
    }

  }
  
//   echo $val;
  ?>`;
    console.log(details);
    alert(details);
    if(details.length>0)
    {
        var map=details.split('|');
    console.log(map);
    alert(map);
    var keyval=[];
    for(var i=1;i<map.length-1;i+=2)
    {
        var y=map[i-1];
        var x=map[i].split(',');
        x=x.filter(function(e){return e!="";})
        keyval[y]=x;

    }
    var selected=[];
    for(var i=0;i<arr.length;i++)
    {
        
        for(var j=0;j<keyval[arr[i]].length;j++)
        {
            selected.push(keyval[arr[i]][j]);
        }
    }
    var remarks=$('#Remarks').val();
    console.log(keyval);
    console.log(selected);
    var status='';
    if(btn_id=='Approve'){status='APPROVED';}
    if(selected.length>0){
        $.post('php/status-update-emp.php',{Remarks:remarks,Status:status,Books:selected}).done(function(data){
        alert(data);
        location.reload(true);
    });

    }

    }
    
  
    

}


});

        </script>

        </head>
        <body>
        <div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      
      <div class="modal-header">
        <h4 class="modal-title">Are you Sure?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      
      <div class="modal-body">
       Remarks <input type="text" name="Remarks" id="Remarks"/>
      </div>

      
      <div class="modal-footer">
      <button type="button" onclick="$.submit()" class="btn btn-success" data-dismiss="modal">YES</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">NO</button>
      </div>

    </div>
  </div>
</div>




        <!-- MODAL********************************************* -->
        <div class="container">
        
       
</div>
        </body>
        </html>