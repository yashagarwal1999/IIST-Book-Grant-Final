<?php
session_start();
if(isset($_SESSION['User']))
{
    // echo $_SESSION['User'];
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
        <!-- <script src="js/view-libst.js"></script> -->
        <script>
        $(document).ready(function(){
            $.redirect=function ()
    {
        
        window.location.replace("index.php");
    }
        });
   
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
        <?php echo 'Welcome '.$_SESSION['User_name'].' '.$_SESSION['Role_name'].' ID: '.$_SESSION['User']; ?>
     </marquee>
                <div class="container bg-light" style="margin-top:3vh; height:80vh;overflow:auto " style="background-color: white;">
<div class="row margin-row" >
<div class="col-3 " style="border:silver">
   
            
<ul class="nav nav-tabs flex-column  border">
                    <!-- <li class="nav-item ">
                                <a  class="nav-link nav-link-color " data-toggle="tab" href="#Add-Book">Add Book</a>
                        </li> -->
                    <li class="nav-item"><a  class="nav-link nav-link-color " data-toggle="tab" href="#PENDING"> PENDING Request</a></li>
                    <!-- <li class="nav-item"><a target="_blank"  href="view-emp.php"> PENDING Request</a></li> -->
                        
                        
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Approved">Approved Request</a></li>
                        <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Rejected">Rejected Request</a></li>
                        <!-- <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Resubmit">Resubmit Request</a></li> -->
                       
                        <!-- <li class="nav-item"><a  class="nav-link nav-link-color" data-toggle="tab" href="#Balance">Check Balance</a></li> -->
                        <li class="nav-item"><a  class="nav-link nav-link-color" onclick="$.redirect()">Logout</a></li>
                    </ul>
                    
                  

                    

</div>
<div class="col-9" style="padding-left:10vh; overflow:auto">
          <div id="MyTabs" class="tab-content">
                        


                        <div id="Logout" class="tab-pane fade in ">
                        
                        </div>
        <div id="PENDING" class="tab-pane fade in   ">
          
            <!-- <p id="Not-found1">No PENDING Request found</p> -->
            <div id="content1">
            <form target="_blank" action="view-emp.php" method="post">
            <input class="btn btn-primary" type="hidden" name="Status" value="Pending"/>
            <input class="btn btn-primary" type="submit" name="submit" value="View"/>
            </form>
            </div>


        </div>


        <div id="Approved" class="tab-pane fade in   ">
          
          <!-- <p id="Not-found2">No Approved Request found</p> -->
          <div id="content2">
          <form target="_blank" action="view-emp.php" method="post">
            <input class="btn btn-primary" type="hidden" name="Status" value="Approved"/>
            <input class="btn btn-primary" type="submit" name="submit" value="View"/>
            </form>
          
          </div>


      </div>


      <div id="Rejected" class="tab-pane fade in   ">
          
          <p id="Not-found3">No Rejected Request found</p>
          <div id="content3">
          
          </div>


      </div>

      <!-- <div id="Resubmit" class="tab-pane fade in   ">
          
          <p id="Not-found4">No Resubmit Request found</p>
          <div id="content4">
          
          </div>


      </div> -->


</div>
</div>
                    
        </div>
        </div>
        
    </body>
    </html>