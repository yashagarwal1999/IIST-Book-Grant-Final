<?php
 

session_start();

if(isset($_POST["Login"])){

//Z012KrsyFFpdWPai
// $connection= new mysqli('127.0.0.1','root123','Z012KrsyFFpdWPai','iist_librarygrant');
include('php/mysqli.php');

$student="student";
$faculty="Faculty";
$libst="Library Staff";
if($connection)
{
    
    
    if(empty($_POST["Username"]) || empty($_POST["Password"]))
    {
        
        header('Location:index.php?Empty=Please fill out all fields');
        
    }
   
    $query="SELECT User_email,User_name,User_id,Role_id,User_password from tbl_user where User_id='".$_POST['Username']."' and User_password='".md5($_POST['Password'])."'";
    $result=$connection->query($query);
    echo mysqli_num_rows($result);
    echo "<br>";
    
    if(mysqli_num_rows($result)==0)
    {
        
        header('Location:index.php?Empty=User-id or password Incorrect');
    }
    else{
        
        $row=$result->fetch_assoc();
        $_SESSION['User_name']=$row['User_name'];
        $roles=explode('#',$row["Role_id"]);
        $_SESSION['User_email']=$row['User_email'];
   

        foreach ($roles as $role)
        {
            
            $query="SELECT Role_name from tbl_roles where Role_id='".$role."'";
            $res=$connection->query($query);
            
           echo mysqli_num_rows($result);
            $str=$res->fetch_assoc();
            echo $str["Role_name"];
            if(strtolower($str["Role_name"])==strtolower($student))
            {
                
           $_SESSION['User']=$_POST['Username'];
           $_SESSION['Role_id']=$role;
           $_SESSION['Role_name']=$str["Role_name"];
            $_SESSION['FromLogin']=1;
           if(isset($_SESSION['User'])){
            $connection->close();
            header('Location:dashboard.php');
           }
                
                
           }

           else  if(strtolower($str["Role_name"])==strtolower($faculty))
           {
               
          $_SESSION['User']=$_POST['Username'];
           $_SESSION['FromLogin']=1;
           $_SESSION['Role_id']=$role;
           $_SESSION['Role_name']=$str["Role_name"];
          if(isset($_SESSION['User'])){
           $connection->close();
           header('Location:dashboardF.php');
          }
               
               
          }

          else if(strtolower($str["Role_name"])==strtolower($libst)){
            $_SESSION['User']=$_POST['Username'];
            $_SESSION['FromLogin']=1;
            $_SESSION['Role_id']=$role;
            $_SESSION['Role_name']=$str["Role_name"];
            if(isset($_SESSION['User'])){
                $connection->close();
                header('Location:dashtry.php');
               }
          }
          else{
              $query="Select * from tbl_lib_order where role_id='".$role."' and status_of_approval='YES'";
              $res=$connection->query($query);
              $_SESSION['User']=$_POST['Username'];
              $_SESSION['FromLogin']=1;
              $_SESSION['Role_id']=$role;
              $_SESSION['Role_name']=$str["Role_name"];
              if(mysqli_num_rows($res)>0)
              {
                $connection->close();
                header('Location:dashEmp.php');
              }

          }
        

        }
        
       
        
    }

    

}


}
else {
    
    echo 'Error in session.';
    $connection->close();
    header("Location:index.php?Empty=Error In connection");
}

?>