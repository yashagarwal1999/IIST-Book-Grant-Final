<?php
// $q=$_POST["q"];
session_start();
include('php/mysqli.php');
//If one insertion fails in case if flag revert back all insertions and reset auto increment also delte the book id added and 
//give error.
//If any error in insertion look for foreign key constraint.
// After anyone approves PROCEDURE next_flag(IN bk_id bigint(20),OUT val varchar(2))
$faculty="F";


// echo $_SESSION['User'];
$query="SELECT sem_id,status_of_activation from tbl_lib_account where user_id='".$_SESSION['User']."' ";
  $result=$connection->query($query);
  $row=$result->fetch_assoc();
  if($row['status_of_activation']=="NA")
  {
      echo 'Your account is not activated to add books';
  }
  
  else{
    $_SESSION['sem_id']=$row['sem_id'];
    $queryx="Select sem_start_date,sem_end_date from tbl_lib_semester where sem_id='".$row['sem_id']."'";
    $resultx=$connection->query($queryx);
    $rowx=$result->fetch_assoc();
    $date=date("Y-m-d");
    $date=date('Y-m-d',strtotime($date));
    $startdate=date('Y-m-d',strtotime($rowx['sem_start_date']));
    $enddate=date('Y-m-d',strtotime($rowx['sem_end_date']));
    echo $date;
    if($date<$startdate && $date>$enddate)
    {
        echo 'Semester over';
    }
    else{
  
    if(strlen($_POST['title_book'])==0 || strlen($_POST['author_book'])==0 || strlen($_POST['publish_book'])==0)
    {
        echo 'All fields are required';
    }

else{   
    echo 'Valid Semester';
    $query="Insert into tbl_lib_books(title,author,publisher,sem_id,user_id,faculty_id) values ('".$_POST['title_book']."', '".$_POST['author_book']."',
     '".$_POST['publish_book']."','".$row['sem_id']."',  '".$_SESSION['User']."', '".$_POST['faculty_book']."' )"; 
    $book_id=0;
    
   
    // print_r($)
    
        $query="Insert into tbl_lib_books(title,author,publisher,sem_id,user_id,faculty_id,borrow_category_id) 
        values ('".$_POST['title_book']."', '".$_POST['author_book']."',
        '".$_POST['publish_book']."','".$row['sem_id']."',  '".$_SESSION['User']."', '".$_POST['faculty_book']."', 
        '".$_POST['Category_id']."' )"; 
    
    
   
        
    
    //echo $_POST['faculty_book'];
      if($connection->query($query)==TRUE)
        {
            $book_id=$connection->insert_id;
            echo 'Book added succesfully';

            $query2="Select * from tbl_lib_order where status_of_approval='YES' order by(order_sequence) asc";
            $result2=$connection->query($query2);
            
             echo 'Yash'.mysqli_num_rows($result2).'<br>';
                $flag=0;
                $first_flag=0;
               
                    // if($flag==0){
                            $answer=$result2->fetch_object();
                            $orde=$result2->fetch_object();
                            $query3="Insert into tbl_lib_flag(book_id,status_of_approval,role_id,user_id,next_order_id) values ('".$book_id."','PENDING', '".$answer->role_id."', '".$_POST['faculty_book']."' , '".$orde->order_id."' )";
                                if($connection->query($query3)==TRUE)
                            {
                                $first_flag=$connection->insert_id;
                                echo 'First:'.$first_flag.'<br>';
                            }
                            echo $flag.'X';
                            $flag=$flag+1;

                        // }
                    // else{

                        // $query3="Insert into tbl_lib_flag(book_id,status_of_approval,role_id) values ('".$book_id."','NOT RECEIVED', '".$answer->role_id."')";
                        // if($connection->query($query3)==TRUE)
                        // {
                        //     echo $flag.'X';
                        //     $flag=$flag+1;
                        // }
        
                    // }
                   
                
                $query4="Update tbl_lib_books set first_flag_id='".$first_flag."' where book_id='".$book_id."'";
                if($connection->query($query4)==TRUE)
                {
                    echo 'Added first_flag succesfully';
                }
                else{
                    echo 'error in adding flag';
                }


        }
        else{
            echo 'Error in adding book';
        }

  
    
    

  }
}
}
$connection->close();

?>
