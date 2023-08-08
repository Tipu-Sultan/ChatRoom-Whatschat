<?php
session_start();
include 'chatDB.php';
 $uid = $_SESSION['user_Id'];
 date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
$ls = date('d-m H:i:s')." Last Seen";
$update = mysqli_query($con,"update users set status=0 where id=$uid");
$update1 = mysqli_query($con,"update friends set last_seen='$ls' where to_user_id=$uid");
if(session_destroy()) {
 header("Location: login.php");
}
?>