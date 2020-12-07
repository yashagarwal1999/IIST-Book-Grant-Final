<?php
session_start();
if(isset($_POST['Submit']))
{

    $id=$_SESSION['Id'];
    
    
    $query="UPDATE temp set mobile='".$_POST['Mobile']."',  Pemail='".$_POST['PEmail']."', oemail='".$_POST['OEmail']."' where Id='".$id."'";
    $connection=new mysqli("localhost","root123","Z012KrsyFFpdWPai","iist_librarygrant");
    if(!$connection)
   {
       echo 'Error in connection';
   }
  else{
    $result=$connection->query($query);
    if($result==true)
    {
        echo 'Updated in database';

    }
    else{
        echo 'Error in writing database';
    }
    

}



}
else{

    echo 'error';
}

?>