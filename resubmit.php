<?php
include('php/mysqli.php');
$query34="Select * from tbl_lib_books where book_id='".$_POST['bk_id']."'; ";
$res34=$connection->query($query34);
if(!$res34)
{
    die('book not found'+$connection->error);

}
$query35="Select* from tbl_lib_flag where book_id='".$_POST['bk_id']."' and status_of_approval='RESUBMIT' limit 1";
$res35=$connection->query($query35);
if(!$res35)
{
    die($connection->error);

}
$row=$res35->fetch_object();
$temp=$res34->fetch_object();
$query34="INSERT INTO tbl_lib_resubmit
(title, author, publisher, faculty_id, book_id,role_id,Remarks ) VALUES 
('".$temp->title."', '".$temp->author."', '".$temp->publisher."', '".$temp->faculty_id."',
'".$temp->book_id."', '".$row->role_id."', '".$row->Remarks."' );";
if($connection->query($query34)==TRUE)
{
    $insertid=$connection->insert_id;
    $query34="UPDATE `tbl_lib_flag` SET first_resubmit_id='".$insertid."', status_of_approval='PENDING' where flag_id='".$row->flag_id."'";
    if($connection->query($query34)==TRUE)
    {
        echo 'UPDATED flag table';
    }
    else{
        die($connection->error);
    }
}
$query34="UPDATE `tbl_lib_books` SET title='".$_POST['Title']."', author='".$_POST['Author']."', 
publisher='".$_POST['Publisher']."', faculty_id='".$_POST['FacId']."' where book_id='".$_POST['bk_id']."' ";
if($connection->query($query34)==TRUE)
{
    echo 'REsubmit successfully';
}
else{
    die($connection->error);
}

$connection->close();
 ?>