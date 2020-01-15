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
        <script src="application/jquery.min.js"></script>
        <script src="application/popper.min.js"></script>
        <script src="application/bootstrap.min.js"></script>
        <!-- <script src="js/logout.js"></script> -->
        <script src="js/view-libst.js"></script>
        <script>
        $(document).ready(function(){
          $.redirect=function ()
    {
        
        window.location.replace("index.php");
    }
});
        function submit_date()
        {
            // alert('hiii');
             var x=$('#start-date').val();
             if(x.length==0)
             {
                 alert('select a date');
                 return false;
             }
            var start=new Date(x);
            alert(start);
            var y=$('#end-date').val();
             if(y.length==0)
             {
                 alert('select a date');
                 return false;
             }
            var end=new Date(y);
            console.log(end);
            var sdate=start.getTime();
            var edate=end.getTime();
            if(edate<=sdate)
            {
                alert('End date should be greater');
                return false;
            }
            var amount=$('#amount').val();
            var sem_id=$('#semester').val();
            alert(sem_id);
            $.update_date(x,y,ammount,sem_id);
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
        <?php echo 'Welcome '.$_SESSION['User_name'].' Library Staff ID: '.$_SESSION['User']; ?>
     </marquee>
                <div class="container bg-light" style="margin-top:3vh; height:80vh;overflow:auto " style="background-color: white;">
<div class="row margin-row" >
<div class="col-3 " style="border:silver">
   
            
<ul class="nav nav-tabs flex-column  border">
                    <!-- <li class="nav-item ">
                                <a  class="nav-link nav-link-color " data-toggle="tab" href="#Add-Book">Add Book</a>
                        </li> -->
                    <li class="nav-item"><a  class="nav-link nav-link-color " data-toggle="tab" href="#PENDING"> PENDING Request</a></li>
                        
                        
                        
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Approved">Approved Request</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Rejected">Rejected Request</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Resubmit">Resubmit Request</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Sem-Dates">Change Sem Dates</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Report">Generate Report</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" onclick="$.redirect()">Logout</a></li>
                    </ul>
                    
                  

                    

</div>
<div class="col-9" style="padding-left:10vh; overflow:auto">
          <div id="MyTabs" class="tab-content">
                        


                        <div id="Logout" class="tab-pane fade in ">
                        
                        </div>
        <div id="PENDING" class="tab-pane fade in   ">
          
            <p id="Not-found1">No PENDING Request found</p>
            <div id="content1">
            
            </div>


        </div>

        <div id="Report" class="tab-pane fade in   ">
          
          <p id="Not-found6"><a href="generate-reports.php" target="_blank"><button class="btn btn-primary">Generate reports
          </button></a></p>
          <div id="content6">
          
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

      <div id="Sem-Dates" class="tab-pane fade in   ">
          
          <p id="Not-found5">No Sem Dates  found</p>
          <div id="content5">
          
          <div class="row padding">
       <div class="col-3">Start Date:</div>
       <div class="col-9">
           <input id="start-date" type="date" required/>
       </div>
   
   
   </div>
   <div class="row padding">
       <div class="col-3">End Date:</div>
       <div class="col-9">
           <input id="end-date" type="date" required/>
       </div>
       
   
   </div>
   
   <div class="row padding">
       <div class="col-3">Choose Semester</div>
       <div class="col-9">

       <?php include('php/get-semester.php');?>
           
       </div>
   </div>

         
   <div class="row padding">
       <div class="col-3">Maximum Amount</div>
       <div class="col-9">

       <input id="amount" type="number" required>
           
       </div>
   </div>

   <div class="row padding">
   <div class="col-4"></div>
   <div class="col-8">
   <buttton onclick="return submit_date()" class="btn btn-primary">Submit</button>
   </div>
   </div>




          </div>


      </div>
      

<!-- 
        <div id="View-All" class="tab-pane fade in   " style="overflow:auto;">
          
            <p id="Not-found5">No  Request Records found</p>
            
            <div id="content5">
            
            </div> -->
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