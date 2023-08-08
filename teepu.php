<?php
session_start(); 
include 'chatDB.php';
$sid = $_SESSION['user_Id'];
if(isset($_POST['from_User']))
{
  $chatArray = array("err");
   $from_User = $_POST['from_User'];
   $to_User = $_POST['to_User'];
   $message = $_POST['message'];
   $fetch = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM users WHERE id=$sid"));
   $simg = $fetch['image'];
   $time = time();

    if(!empty($_FILES['file-inputs'])){
    $targetDir = "chatfiles/";
    $allowTypes = array('jpeg', 'jpg', 'png', 'webp','gif','pdf','sql','mp4');
    $maxsize = 3145728;
    $fileName = basename($_FILES['file-inputs']['name']);
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $rand =bin2hex(random_bytes(2));
    $newName = "whatschat".$rand.$from_User.'.'.$extension;
    $targetFilePath = $targetDir.$newName;

    // Check whether file type is valid
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    if(in_array($fileType, $allowTypes)){
    if (($_FILES['file-inputs']['size'] >= $maxsize) || ($_FILES['file-inputs']['size'] == 0)) {
    $chatArray = array("error"=>"yes","msg"=>"File to large");
    }else{
    if(move_uploaded_file($_FILES['file-inputs']['tmp_name'], $targetFilePath)){
    $sql1 = mysqli_query($con,"insert into message (From_User, To_User,session_img, message,chat_time,status) values('$from_User','$to_User','$simg','$newName',$time,1)");
    $unread = mysqli_query($con,"update friends set unread=unread+1 where session_id=$to_User and  to_user_id=$from_User");
    $chatArray = array("error"=>"no","msg"=>"mesaage insert as file");
    }
    }
    }
    }else{
   $sql = mysqli_query($con,"insert into message (From_User, To_User,session_img, message,chat_time,status) values('$from_User','$to_User','$simg','$message',$time,1)");
   $update1 = mysqli_query($con,"update friends set last_msg='$message',unread=unread+1 where session_id=$to_User and  to_user_id=$from_User");
   $chatArray = array("error"=>"no","msg"=>"mesaage insert as text");
    }
echo json_encode($chatArray);
}
 ?>

 <?php 
 if(isset($_POST['type']) && $_POST['type']=="newfriends")
{
   $output = "";
   $query = $_POST['query'];
         $users = mysqli_query($con,"SELECT * FROM users WHERE user LIKE '%{$query}%' or email LIKE '%{$query}%'") or die(mysqli_error($con));
          $class = "";
          $count = mysqli_num_rows($users);
          if($count>0){
           while($res = mysqli_fetch_assoc($users))
           {
            if($res['status'] == 1)
            {
              $class = "online_icon";
            }
            else{
              $class = "offline_icon";
            }
            $output .='<li class="active">
              <div class="d-flex bd-highlight">
                <div class="img_cont">
                  <img src="uploads/'.$res['image'].'" class="rounded-circle user_img">
                  <span class="'.$class.'"></span>
                </div>
                <div class="user_info">
                  <span>'.$res['user'].'</span>
                  <p>'.$res['email'].'</p>
                  <a href="?addfid='.$res['id'].'" class="btn btn-primary btn-sm mx-2">Make a friend</a>
                </div>
              </div>
            </li>';
          }
       }else{
         $output .='<h5 class="text-center text-danger">Users not found</h5>';
       }
         echo $output;
}

  ?>




 <?php 
 if(isset($_POST['type']) && $_POST['type']=="added_friends")
{
  $output = "";
   $query = $_POST['query'];
         $existfriends = mysqli_query($con,"SELECT * FROM friends WHERE to_user LIKE '%{$query}%' and session_id=$sid ") or die(mysqli_error($con));
          $count = mysqli_num_rows($existfriends);
          if($count>0){
           while($res = mysqli_fetch_assoc($existfriends))
           {
            
            $output .='
             <a href="?to_User='.$res['to_user_id'].'" style="text-decoration: none;">
            <li class="active">
              <div class="d-flex bd-highlight">
                <div class="img_cont">
                  <img src="uploads/'.$res['image'].'" class="rounded-circle user_img">
                  <span class=""></span>
                </div>
                <div class="user_info">
                  <span>'.$res['to_user'].'</span>
                  <p>'.$res['last_seen'].'</p>
                  <p>'.$res['last_msg'].'</p>
                </div>
              </div>
            </li>
            </a>';
          }
       }else{
         $output .='<h5 class="text-center text-danger">Users not found</h5>';
       }
         echo $output;
}

  ?>

<?php 
if(!empty($_FILES['file']) && isset($_POST['changepic'])){ 
  $upload = array("ferr");
  include 'chatDB.php';
    $user_id = $_POST['user_id'];
    $fetch1 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM users WHERE id=$user_id"));
   $imgID = $fetch1['image'];
    // File upload configuration 
    $targetDir = "uploads/"; 
    $allowTypes = array('jpeg', 'jpg', 'png', 'webp','gif'); 
    $maxsize = 1048576;
    $fileName = basename($_FILES['file']['name']);

    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $rand =bin2hex(random_bytes(2));
    $newName = $rand.$user_id.'.'.$extension;

    $targetFilePath = $targetDir.$newName; 
     
    // Check whether file type is valid 
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
    if(in_array($fileType, $allowTypes)){ 

if (($_FILES['file']['size'] >= $maxsize) || ($_FILES['file']['size'] == 0)) {
    $upload = array("error"=>"yes","msg"=>"File size too large");

}else{

        if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)){
            if ($imgID!="user.png") {
            unlink("uploads/"."{$imgID}");
            }
         $upload_query1 = mysqli_query($con,"update users set image='$newName' where id='$user_id' ");
         $upload_query2 = mysqli_query($con,"update friends set image='$newName' where to_user_id='$user_id' ");
         $upload_query3 = mysqli_query($con,"update message set session_img='$newName' where From_User='$user_id' ");
        $upload = array("error"=>"no","msg"=>"profile changed");
        header("Location:index.php");
        die();
        }
      }
  }else{
    $upload = array("error"=>"yes","msg"=>"File type not matched");
  }
  echo json_encode($upload);
}

 ?>

 <?php 
if(isset($_POST['countmsg']))
{
$to_User = $_POST['to_User'];
  $output1 = "";
  $countmsg = mysqli_query($con,"SELECT COUNT(message) as msgs FROM message WHERE From_User='".$_SESSION['user_Id']."' and To_User='".$to_User."' ") 
                  or die("Failed to query database".mysql_error());
                  $data2 = mysqli_fetch_array($countmsg);
                  // $data3 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM users WHERE id=$to_User"));
                  $output1 .=''.$data2['msgs'].' messages';
                  echo $output1;

}

 ?>

 <?php 
if(isset($_POST['type']) && $_POST['type']=="remove_item")
{
$mid = $_POST['msgid'];
$data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM message WHERE id=$mid"));
$imgID = $data['message'];
unlink("chatfiles/"."{$imgID}");

$del = mysqli_query($con,"DELETE FROM message WHERE id=$mid");
}

 ?>

 