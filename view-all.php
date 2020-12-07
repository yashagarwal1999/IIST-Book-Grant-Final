<?php
session_start();
include('php/mysqli.php');

$query31="Select * from tbl_lib_books where user_id='".$_SESSION['User']."' order by book_id ";
$res31=$connection->query($query31);
if(!$res31)
{
    die($connection->error);
}
$data=array();
$books=array();
while($temp=$res31->fetch_object()){

    $data[$temp->book_id][]=$temp;
    $books[]=$temp->book_id;
}

$rolenames=array();
$query32="Select Role_id, Role_name from tbl_roles";
$res32=$connection->query($query32);
if(!$res32)
{
    die($connection->error);
}
while($temp=$res32->fetch_object()){
    
    $rolenames[$temp->Role_name]=$temp->Role_id;
}

$books=join(',',$books);
$query33='';
if($_POST['Status']=='APPROVED'){

    $query33="Select book_id from tbl_lib_approved where book_id in ($books)";
    $res33=$connection->query($query33);
    if(!$res33)die($cpnnection->error);
    $books=array();
    while($temp=$res33->fetch_object())
    {
        $books[]=$temp->book_id;

    }
    
    if(count($books)>0)
    {
        $books=join(',',$books);
        
    $query33="Select flag_id,book_id,status_of_approval,Remarks,flag_timestamp,role_id from tbl_lib_flag 
    where book_id in (Select book_id from tbl_lib_flag where  
    status_of_approval='".$_POST['Status']."' and next_order_id 
    is NULL and book_id in ($books) order by flag_id desc ) order by book_id ,flag_id; ";
    }
    else{
        $query33="";
    }

  
    
}
elseif($_POST['Status']!='ALL'){
$query33="Select flag_id,book_id,status_of_approval,Remarks,flag_timestamp,role_id from tbl_lib_flag 
where book_id in (Select book_id from tbl_lib_flag where book_id in ($books) 
and status_of_approval='".$_POST['Status']."' order by flag_id desc ) order by book_id ,flag_id ";}
else{
    $query33="Select flag_id,book_id,status_of_approval,Remarks,flag_timestamp,role_id from tbl_lib_flag 
    where book_id in (Select book_id from tbl_lib_flag where book_id in ($books) 
    order by flag_id desc ) order by book_id ,flag_id ";
    
}

$flags=array();
if(strlen($query33)>0)
{
$res33=$connection->query($query33);
$books=array();
while($temp=$res33->fetch_object())
{
    if($temp->Remarks==null){$temp->Remarks='NONE';}
    $temp->role_id=array_search($temp->role_id,$rolenames);
$flags[$temp->book_id][]=$temp;
$books[]=$temp->book_id;
}

if(count($books)==0)
{
    die('NO  BOOKS FOUND');
}
$books=join(',',$books);

$query33="Select y.User_name as name,x.faculty_id as faculty_id,x.bill_id as bill_id,x.book_id as book_id,x.title as 
title,x.author as author,x.publisher as publisher ,x.book_added as book_added 
from tbl_lib_books as x,tbl_user as y where x.book_id in ($books) and x.faculty_id=y.User_id order by x.book_id";
$res33=$connection->query($query33);
if(!$res33){die($connection->error);}
$tosend=array();
while($temp=$res33->fetch_object())
{
    
    $tosend['data'][]=$temp;
    $tosend['flags'][]=$flags[$temp->book_id];
}
echo json_encode($tosend);
}



$connection->close();
?>