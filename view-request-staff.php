<?php

session_start();
// $off=$_POST['offse'];
// $lim=$_POST['limit'];
$type=$_POST['RequestType'];
include('php/mysqli.php');
$user=$_SESSION['User'];
$p='';
switch($type)
{
    case 1:
        $p='PENDING';
    break;
    case 2: $p='APPROVED';
break;
case 4: $p='RESUBMIT'; break;
case 3: $p='REJECTED'; break;

}
$provisionResubmit=0;
$query22="Select * from tbl_lib_order where role_id='".$_SESSION['Role_id']."'";
$res22=$connection->query($query22);
if(!$res22 || mysqli_num_rows($res22)==0)
{
    echo 'ERROR in finding the order of the User '.$connection->error;

}
else{
    $temp=$res22->fetch_object();
    if($temp->resubmit_provsion=='YES'){$provisionResubmit=1;}
    
$query11="Select w.User_name as User_name,z.faculty_id as faculty_id,z.details_added as Faculty_Timestamp ,z.subject_coverage as 	subject_coverage ,y.status_of_approval as status_of_approval,y.flag_timestamp as 
flag_timestamp,x.book_id as book_id ,x.title as title,x.author as author,x.publisher 
as publisher,x.book_added as book_added from tbl_lib_books
 as x,tbl_lib_flag as y,tbl_lib_faculty_book as z,tbl_user as w where  x.book_id=y.book_id and y.status_of_approval='".$p."' 
 and y.role_id='".$_SESSION['Role_id']."' and x.book_id=z.book_id and w.User_id=z.faculty_id order by(x.book_added) desc ";
// print_r($query11);
if($type==5)
{
    $query11="Select y.status_of_approval as status_of_approval,y.flag_timestamp as flag_timestamp,x.book_id as book_id ,x.title as title,x.author as author,x.publisher as publisher,x.book_added as book_added,'".$_SESSION['User_name']."' as name from tbl_lib_books as x,tbl_lib_flag as y where  x.book_id=y.book_id and y.role_id='".$_SESSION['Role_id']."' order by(x.book_added) desc ";
    // print_r($query11);
}
$result11=$connection->query($query11);
$row=array();
$flags=array();
$books=array();
// $row[]=(array("Provision-Resubmit"=>$provisionResubmit));
// $row[]=array('User_name'=>$_SESSION['User_name'] );
// $entryx=$result11->fetch_all();
while($entry=$result11->fetch_object())
// foreach($entryx as $entry)
{
// echo $entry->;
    $row[]=$entry;
    $books[]=$entry->book_id;

    
}
if(count($books)==0)
{
    die('No books found');
}
else{
    $books=join(",",$books);
    $query23="Select * from tbl_lib_flag where book_id in ($books) order by(book_id)";
    $result23=$connection->query($query23);
    if(!$result23)
    {
        die($connection->error);
    }
    else{
    
        while($e=$result23->fetch_object())
        {
            $flag[]=$e;
        }
    }
    // print_r($books);
    $query23="Select * from tbl_lib_bills where book_id in ($books) order by(book_id)";
$res23=$connection->query($query23);
if(!$res23)
{
    die($connection->error);
}

else{
    // $bill_loc=array();
$bills=array();
while($b=$res23->fetch_object())
{
    $bill_loc=$b->bill_location;
    $filenames=array();
    // print_r($b);

    // if(is_dir($bill_loc))
    // {
    //     if($dh=opendir($bill_loc))
    //     {
    //         while(($f=readdir($dh))!==false)
    //         {
    //             $filenames[]=$f;
    //         }
    //     }
    //     closedir($dh);
    // }
    foreach(glob($b->bill_location.'/*.*') as $fname)
{
    $filenames[]=$fname;
    // echo 'yash';
    // print_r($fname);
}
// print_r('files'.count($filenames));
$bills[]=array("book_id"=>$b->book_id,$b->book_id=>$filenames,"Bill_No"=>$b->bill_no,"Bill_Date"=>$b->bill_date,"Bill_Amount"=>$b->amount,"Bill_Upload"=>$b->upload_timestamp);

}


// echo 'b'.$b->bill_location.'<br>';

}

    
    echo json_encode(array("data"=>$row,"flags"=>$flag,"bills"=>$bills));
}


}


$connection->close();
?>
