<?php 
session_start();
$server = "localhost";
$user = "root";
$password = "";
$db = "chatbox";
$con = mysqli_connect($server,$user,$password,$db);
if (isset($_POST['msg']) && $_POST['name']) {
	$name = $_POST['name'];
	$msg = $_POST['msg'];
	$sid = $_POST['ids'];
	$rid = $_POST['rid'];
	$insert = mysqli_query($con,"insert into test(name,sid,rid,message)values('$name',$sid,$rid,'$msg')");
}

if (isset($_POST['fetch'])) {
	$output = "";
	$uid = $_POST['ids'];
	$rid = $_POST['rid'];
	$data = mysqli_query($con,"SELECT * from test WHERE (sid='".$uid."' AND rid='".$rid."') OR (sid='".$rid."' AND rid='".$uid."') "); 
	while($res = mysqli_fetch_array($data))
	{
		if ($res['sid'] == $uid) {
			$output .='<p>'.$res['name'].'</br>'.$res['message'].'</p>';
		}else{
			$output .='<p style="color:red;margin-left:100px;">'.$res['name'].'</br>'.$res['message'].'</p>';
		}
	}
	echo $output;
}
 ?>