<?php

        $Faculty="F";
        $query6="Select User_id,User_name,Role_id from tbl_user where Role_id like '%".$Faculty."%'";
        $result=$connection->query($query6);
       
        echo '<select class="faculty" name="faculty">';
        while($res=$result->fetch_object())
        {

          $arr=explode('#',$res->Role_id);
          $check=0;
          foreach($arr as $fac){
            if($fac==$Faculty){
              $check=1;
            break;
            }
          }
          if($check==1){
           echo '<option value="'.$res->User_id.'">';
           echo "$res->User_name" ;
           echo "</option>";
          }
        

        }
        echo '<\select><br>';
        
        ?>
