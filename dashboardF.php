
<?php
session_start();
if(isset($_SESSION['User']))
{
}
else
{
header("Location:index.php?Empty=Invalid Login");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="application/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <!-- <link rel="stylesheet" href="accordian/jquery-ui.css"> -->
        <!-- <script type="text/javascript" src="/test/wp-content/themes/child/script/jquery.jcarousel.min.js"></script> -->
        <!-- <script type="text/javascript" src="/Scripts/jquery-3.1.1.min.js"></script>      
<script type="text/javascript" src="/Scripts/jquery-ui-1.12.1/jquery-ui.min.js"></script>  -->
<!-- <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script> -->
        <script src="application/jquery.min.js"></script>
        <script src="application/popper.min.js"></script>
        <script src="application/bootstrap.min.js"></script>
        <!-- <script src="js/add-book.js"></script>
        <script src="js/view-last.js"></script>
        <script src="js/view-all.js"></script> -->

        <script src="js/faculty.js"></script>
        <!-- <script src="js/logout.js"></script> -->
        <!-- <script src="js/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css" href="jquery-ui.css"> -->
        <!-- <script src="accordian/jquery-1.12.4.js"></script> -->
  <!-- <script src="accordian/jquery-ui.js"></script> -->
    <script >
    
    $(document).ready(function(){

      $.redirect=function ()
    {
        
        window.location.replace("index.php");
    }
});
        var state;
    function approve( x, y)
    {

        var subject=document.getElementById("subject"+x).value;
        if( subject.length==0)
        {
            alert("Subject Coverage is compulsory");
            return;
        }


        document.getElementById("r"+x).style.display="block";
        document.getElementById("b"+x).style.display="block";
        state=x+" "+y;

    }

    function status_book(x){
        
        var arr=state.split(" ");
        var subject=document.getElementById("subject"+arr[0]).value;
        
        
        if( subject.length==0)
        {
            alert("Subject Coverage is compulsory");
            return;
        }

        if(x==2)
        {
            document.getElementById("r"+arr[0]).style.display="none";
        document.getElementById("b"+arr[0]).style.display="none"; 
        return;
     
        }
        else{
            var a=["APPROVED","REJECTED","RESUBMIT" ];
            var remark=document.getElementById("remark"+arr[0]).value;
            // alert(remark);
            var book_cat_id=document.getElementById("select"+arr[0]).value;
            // alert(book_cat_id);
            console.log(remark);
            $.submit(book_cat_id,arr[0],remark,a[arr[1]-1],subject);
        }
    }
    </script>




        <!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script> -->
    </head>
    
    <!-- <body style="background-color:rgba(192, 192, 192, 0.959);"> -->
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
        <?php echo 'Welcome '.$_SESSION['User_name'].' Faculty ID: '.$_SESSION['User']; ?>
     </marquee>
                <div class="container bg-light" style="margin-top:3vh; height:80vh;overflow:auto " style="background-color: white;">
<div class="row margin-row" >
<div class="col-3 " style="border:silver">
   
            
                    <ul class="nav nav-tabs flex-column  border">
                    <!-- <li class="nav-item ">
                                <a  class="nav-link nav-link-color " data-toggle="tab" href="#Add-Book">Add Book</a>
                        </li> -->
                    <li class="nav-item"><a  class="nav-link nav-link-color " data-toggle="tab" href="#View-Requests">View Active Request</a></li>
                        
                        
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#View-All">View All Request</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Approved">Approved</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Rejected">Rejected</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Resubmit">Resubmit</a></li>
                        
                        <li class="nav-item"><a  class="nav-link nav-link-color" onclick="$.redirect()">Logout</a></li>
                    </ul>
                    
                  

                    

</div>
<div class="col-9" style="padding-left:10vh; overflow:auto">
          <div id="MyTabs" class="tab-content">
                        


                        <div id="Logout" class="tab-pane fade in ">
                        
                        </div>
        <div id="View-Requests" class="tab-pane fade in   ">
          
            <p id="Not-found1">No Active Request found</p>
            <div id="content1">
            
            </div>


        </div>


        <div id="Approved" class="tab-pane fade in   ">
          
          <p id="Not-found2">No Approved Request found</p>
          <div id="content2">
          
          </div>


      </div>


      <div id="Rejected" class="tab-pane fade in   ">
          
          <p id="Not-found3">No Rejected Request found</p>
          <div id="content3">
          
          </div>


      </div>

      <div id="Resubmit" class="tab-pane fade in   ">
          
          <p id="Not-found4">No Resubmit Request found</p>
          <div id="content4">
          
          </div>


      </div>
      


        <div id="View-All" class="tab-pane fade in   " style="overflow:auto;">
          
            <p id="Not-found5">No  Request Records found</p>
            
            <div id="content5">
            
            </div>
            <!-- <div data-role="content" id="Div1">
    <div id="accordion" data-role="collapsible-set"/>
</div>

        </div> -->

</div>
</div>
                    
        </div>
        </div>
        
    </body>
    </html>