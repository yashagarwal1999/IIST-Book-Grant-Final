<?php
session_start();
include('php/mysqli.php');

if(isset($_FILES['userfile']))
{
    pre($_FILES['userfile']);
    $error_array=array(
        0=>'There is no error. File uploaded with success',
        1=> 'The uploaded file size exceeds the upload_max directive in php.ini',
        2=>'The uploaded file size exceeds the MAX_FILE_SIZE specified in html form',
        3=> 'The uploaded file was partially uploaded',
        4=>'No file was uploaded',
        6=>'Missing a temporary folder ',
        7=>'Failed to write file to the disk',
        8=>'A PHP extension stopped the file upload'
    
        );

        $extensions=array("jpg","jpeg","pdf","doc","docx","txt","png");
        $query19="Select * from tbl_lib_books where book_id='".$_POST['book_id']."' and user_id='".$_SESSION['User']."'";
        $res19=$connection->query($query19);
        $error;
        if(!$res19)
        
        {
            // echo $_POST['book_id'].'   '.$_SESSION['User'];
            echo 'USer and Book uploaded don\'t match try again';
            $error=1;
            echo '-'.$error;
        }
        else{
                    $temp=$res19->fetch_object();
                    $book_req=$temp;
                    $sem_id=$temp->sem_id;
                   
                    // if($temp->bill_id !=NULL)
                    // {
                    //     echo 'Bill Already Uploaded.';
                    // }
                    // else{
            
                                // echo 'sem'.$sem_id;
                    $query19="Select * from tbl_lib_flag where flag_id='".$temp->first_flag_id."' and status_of_approval='APPROVED'" ;
                    $res19=$connection->query($query19);
                    if(!$res19){
                        // echo $_POST['book_id'].'   '.$_SESSION['User'];
                        echo 'Looks like the Faculty is yet to approve your request Error.Bill not uploaded';
                        $error=1;
                    echo '-'.$error;
                    }
                    else{
                        $query19="Insert into tbl_lib_resubmit_bills values ('".$temp->book_id."', '".$temp->bill_id."')";
                        $res19=$connection->query($query19);
                        if(!$res19)
                        {
                         echo 'Error in resubmitting bills Try again '.$connection->error ;
                            $error=1;
                    echo '-'.$error;
                    die();
                        }

                        // $_SESSION['Counter']++;
                        $query20="INSERT INTO tbl_lib_bills(book_id, bill_no, bill_date, amount) values ('".$_POST['book_id']."','".$_POST['BNumber']."','".$_POST['Bdate']."','".$_POST['BAmount']."'); ";
                        $res20=$connection->query($query20);
                        
                        
                        // echo 'XXX '.$insertid.' <br>XXX';
                        // echo $_SESSION['Counter'].' <br>';
                        // print_r($query20);
                        if(!$res20)
                        {
                            echo 'Error in adding bills Try again '.$connection->error;
                            $error=1;
                    echo '-'.$error;
                        }
                        else{
                            
                            $insertid=$connection->insert_id;
                            $query20="UPDATE tbl_lib_books SET bill_id='".$insertid."' where book_id='".$_POST['book_id']."'";
                            $res20=$connection->query($query20);
                            if(!$res20)
                            {
                                echo 'bill_id not added in book table';
                                $error=1;
                    echo '-'.$error;
                            }
                            else{
                                $loc='bills/';
                                $counter=0;
                                $query21="Select * from tbl_lib_semester where sem_id='".$sem_id."'";
                                $res21=$connection->query($query21);
                                if(!$res21)
                                {
                                    echo 'Error in fetching sem of student ';
                                    $error=1;
                    echo '-'.$error;
                                }
                                else{
                                    $temp2=$res21->fetch_object();
                                    $loc=$loc.'/'.$temp2->sem_details.'/';
                                    if(!file_exists($loc))
                                {
                                    mkdir($loc);
                                     echo $loc;
                                    
                                }
            
                                    if(!file_exists($loc.$_SESSION['User']))
                                    {
                                        mkdir($loc.$_SESSION['User']);
                                        // echo 'USer';
                                        
                                        
                                    }
                                    $loc=$loc.$_SESSION['User'].'/';
            
                                    if(!file_exists($loc.$insertid))
                                    {
                                        mkdir($loc.$insertid);
                                        // echo 'USer';
                                        
                                        
                                    }
                                    $loc=$loc.$insertid.'/';
                                    $arr=$_FILES['userfile'];
                                    if($arr['error'])
                        {
                            echo $arr['name'].'- '.$error_array[$arr['error']].'<br>  ';
                        }
                        else{
                                                        $ext=explode('.',$arr['name']);
                                                        $ext=end($ext);
                                                        $ext=strtolower($ext);
                                                        print_r( $arr);

                            
                            if(!in_array($ext,$extensions))
                            {
                                echo $arr['name'].'- '.'Invalide file extension'.'<br>  ';
                                $error=1;
        echo '-'.$error;
                            }
                            else{
                                $filename=$_POST['book_id'].'-'.microtime(true).'-'.$counter;
                                $counter++;
                                move_uploaded_file($arr['tmp_name'],$loc.$filename.'.'.$ext);
                                echo $arr['name'].'-'.$error_array[$arr['error']].'<br>'      ;
                            }
                        }
                    }
                    $query20="UPDATE tbl_lib_bills SET bill_location='".$loc."' where book_id='".$_POST['book_id']."' and bill_id='".$insertid."'";
                    // $query20="UPDATE tbl_lib_bills SET bill_location='".$loc."', bill_counter='.$counter.' where book_id='".$_POST['book_id']."' and bill_id='".$insertid."'";
                    $es20=$connection->query($query20);
                    if(!$res20)
                    {
                        echo $connection->error;
                        $error=1;
                    echo '-'.$error;
                    }
                    else{
                        
                    echo '-'.$error;
                    }
                    $query20="UPDATE tbl_lib_flag SET status_of_approval='PENDING' where status_of_approval='RESUBMIT'
                     and book_id='".$_POST['book_id']."' ";
                    $es20=$connection->query($query20);
                    if(!$res20)
                    {
                        echo $connection->error;
                        $error=1;
                    echo '-'.$error;
                    die();
                    }

}
                            }
                        }
                    
                }
            }
        
            
function pre($array)
    {
        echo '<pre>';
        print_r( $array);
        echo '</pre>';
    }
$connection->close();

?>