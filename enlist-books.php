<?php
session_start();
include('php/mysqli.php');
echo '<div class="table-responsive ">
                   <table class="table table-striped table-bordered" style=" border:2px solid black">
                  <thead> <tr>
                   <th>Book_id</th>
                   <th>View</th>
                   
                   </tr></thead>';
                   
               
                   foreach($_POST['Book_id'] as $p)
                   {
                       echo '<tr>';
                        echo '<td>'.$p.'</td>';
                        echo '<td>'.'<form target="_blank" action="book_info.php" method="post">';
                        echo '<input type="hidden" name="Book_id" value="'.$p.'">
                        <input type="submit" class="btn btn-primary" value="View">';
                       echo '</form></td></tr>';
                   }
                   echo '</table></div>';

$connection->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="application/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="application/jquery.min.js"></script>
        <script src="application/popper.min.js"></script>
        <script src="application/bootstrap.min.js"></script>

        </head>
        </html>