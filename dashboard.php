<?php
if (session_status() == PHP_SESSION_ACTIVE)
{
    session_destroy();
}
session_start();
if(!isset($_SESSION['User']))
{

}
$_SESSION['Request_count']=3;
// 
include('php/mysqli.php');
  $query="SELECT status_of_activation from tbl_lib_account where user_id='".$_SESSION['User']."' ";
  $result=$connection->query($query);
  $row=$result->fetch_assoc();
  
  if($row['status_of_activation']=="NA")
  {
      
  ?>
  <script src="application/jquery.min.js"></script>
     <script type="text/javascript">
      var startdate;
         var enddate;
     $(document).ready(function(){
         $("[href='#Add-Book']").closest("li").hide();
        
        });

       
        
            
       
        
         
         </script>
<?php
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
        <script src="js/add-book.js"></script>
        
        <script src="js/view-all.js"></script>
        
        <script src="application/jquery.form.js"></script>
      <script>
      $(document).ready(function(){

       $.redirect=function ()
    {
        
        window.location.replace("index.php");
    }
        $("[href='#Add-Book']").click(function(){
            var obj='<?php
         $query67="Select sem_id from tbl_lib_account where user_id='".$_SESSION['User']."'";
         $res67=$connection->query($query67);
         if(!$res67)die($connection->error);
         $sem_id=$res67->fetch_object();
         $query67="Select sem_start_date,sem_end_date from tbl_lib_semester where sem_id='".$sem_id->sem_id."' ";
         $res67=$connection->query($query67);
         if(!$res67)die($connection->error);
         $tempo=$res67->fetch_object();
         echo json_encode($tempo)
         ?>';
         obj=JSON.parse(obj);
        
         startdate=obj[0].sem_start_date;
      
        });
      });





   function SubmitBill(id){


document.getElementById("f-"+id).addEventListener("click", function(event){
  event.preventDefault()
});
var x=".preview-"+id;
var files="#images"+id;
var filelist=$(files).val();

var no="#bill-no-"+id;
no=$(no).val();

var amount="#bill-amount-"+id;
amount=$(amount).val();

var bdate="#bill-date-"+id;
bdate=$(bdate).val();

if(no.length==0 || amount.length==0 || bdate.length==0)
{
    alert("ALL FIELDS ARE REQUIRED");
    return false;
}

if(filelist)
{

    $.Upload(x,no,id,amount,bdate);
    
return false;
}
else{
    alert('Please choose a file');
    return false;
}

   }


   function RESubmitBill(id)
   {
       

document.getElementById("f-"+id).addEventListener("click", function(event){
  event.preventDefault()
});
var x=".preview-"+id;
var files="#images"+id;
var filelist=$(files).val();

var no="#bill-no-"+id;
no=$(no).val();

var amount="#bill-amount-"+id;
amount=$(amount).val();

var bdate="#bill-date-"+id;
bdate=$(bdate).val();

if(no.length==0 || amount.length==0 || bdate.length==0)
{
    alert("ALL FIELDS ARE REQUIRED");
    return false;
}

if(filelist)
{

    $.REUpload(x,no,id,amount,bdate);
    
return false;
}
else{
    alert('Please choose a file');
    return false;
}

   }



function resubmit_details(id)
{


    var title=$('#title-'+id).val();
    var author=$('#author-'+id).val();
    var publisher=$('#publisher-'+id).val();
    var facultyId=$('#s-'+id).find(':selected').val();
    
    $.post('resubmit.php',{bk_id:id,Title:title,Author:author,Publisher:publisher,FacId:facultyId}).done(function(data){
        alert(data);
    });

}

          </script>
        

    

            



        
    </head>
    
    
                <body class="bg-dark">   

        
            <nav class="navbar navbar-expand-sm bg-dark" style="color:white;">
                <a class="navbar-brand" href="#">
                        <img class="logo-navbar" src="Logo.jpg">
                </a> 
                <ul class="navbar-nav ">
                        <li class="nav-item">
                               <display6>Indian Institute Of Space Science And Technology</display6>
                               <br><display6>Valiamala,Kerala</display6>
                              </li>
                        
                </ul>
                
                </nav>
                <marquee style="color:white" direction="left" loop="">
        <?php echo 'Welcome '.$_SESSION['User_name'].' Student ID: '.$_SESSION['User']; ?>
     </marquee> 
                <div class="container bg-light" style="margin-top:3vh; height:80vh;overflow:auto " style="background-color: white;">
<div class="row margin-row" >

<div class="col-3 " style="border:silver">
   
            
                    <ul class="nav nav-tabs flex-column  border">
                    <li class="nav-item ">
                                <a  class="nav-link nav-link-color " data-toggle="tab" href="#Add-Book">Add Book</a>
                        </li>
                    <!-- <li class="nav-item"><a  class="nav-link nav-link-color " data-toggle="tab" href="#View-Last">View Latest Request</a></li> -->
                        
                        
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#View-All">View All Request</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#PENDING">PENDING</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#APPROVED">APPROVED</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#RESUBMIT">RESUBMIT</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#REJECTED">REJECTED</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Balance">Check Balance</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" onclick="$.redirect()">Logout</a></li>
                    </ul>
                    
                  

              

</div>
<div class="col-9" style="padding-left:10vh; overflow:auto">
          <div id="MyTabs" class="tab-content">
                        <div id="Add-Book" class="tab-pane fade in">
                          <!--Add BOOk-->
                          
                          <form name="Book-details" method="POST">
                                        <div class="row form-row-space" style="padding-top:20px;">
                                            <div class="col-2">
                                                Title of the book:</div>
                                                <div class="col-4">
                                                <input type="text" name="title" id="title">
                                            </div>
                                        </div>
                                        
                                        <div class="row form-row-space">
                                                <div class="col-2">
                                                        Author of the book:</div>
                                                    <div class="col-4">
                                                            <input type="text" name="author" id="author">
                                                </div>
                                            </div>
                        
                                            <div class="row form-row-space">
                                                    <div class="col-2">
                                                            Publisher of the book:</div>
                                                        <div class="col-4">
                                                                <input type="text" name="publisher" id="publisher">
                                                    </div>
                                                </div>
                                                <br>
                                               

                                                    <div class="row form-row-space">
                                                        <div class="col-2"><p>Do you want borrow amount from other categories like(Optional)
                                                        </p></div>
                                                            <div class="col-4">
                                                               <?php 
                                                               
                                                                echo '<br><br>';
$qeury39="Select x.cat_id_1,y.category_name from tbl_lib_book_conversion as x,tbl_lib_book_category as y where
x.cat_id_1=y.book_cat_id ";
$res39=$connection->query($qeury39);
if(!$res39)
{
    die($connection->error);
}
$categories=array();


while($temp=$res39->fetch_object())
{
$categories[]=$temp;
echo '<input id="borrow" type="checkbox" name="category" value="'.$temp->cat_id_1.'">';

echo $temp->category_name.'<br>';

}
                                                               
                                                               ?>
                                                                </div>
                                                                
                                                        </div>


                                                        <div class="row ">
                                                        <div class="col-2">
                                                                Faculty:</div>
                                                            <div class="col-4">
                                                                    
                                                                    <?php
                                                                    include('faculty-list-menu.php');
                                                                    // die();
                                                                    $connection->close();
                                                                    ?>
                                                                   
                                                        </div>
                                                    </div>

                                                    <div class="row form-row-space">
                                                        <div class="col-2"></div>
                                                            <div class="col-4">
                                                                    <input id="Submit_Book_Request" type="submit" name="Submit_Book_Request" value="Submit Request" class="btn btn-primary">
                                                                </div>
                                                                
                                                        </div>
                                                        
                                       
                        
                                    </form>
                                
                        </div>


                        <div id="Logout" class="tab-pane fade in ">
                        
                        </div>
      


        <div id="APPROVED" class="tab-pane fade in   ">
          
          <p id="notfound1">No APPROVED Request found</p>
          <div id="content1">

          </div>



      </div>


      <div id="REJECTED" class="tab-pane fade in   ">
          
          <p id="notfound2">No REJECTED Request found</p>
          <div id="content2">

</div>


      </div>

      <div id="RESUBMIT" class="tab-pane fade in   ">
          
          <p id="notfound3">No RESUBMIT Request found</p>
          <div id="content3">

</div>



      </div>

      <div id="PENDING" class="tab-pane fade in   ">
          
          <p id="notfound4">No PENDING Request found</p>
          <div id="content4">

</div>


      </div>


      
      <div id="Balance" class="tab-pane fade in   ">
          
          
          <div id="content4">
          <?php include('php/get-balance.php') ?>

</div>


      </div>






        <div id="View-All" class="tab-pane fade in   " style="overflow:auto;">
          
            <p id="notfound5">No  Request Records found</p>
            <div id="content5">
  
            </div>
            

          
</div> 
</div>
                    
        </div>
        </div>
        
    </body>
    </html>

    