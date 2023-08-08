<?php 
session_start();
$server = "localhost";
$user = "root";
$password = "";
$db = "chatbox";
$con = mysqli_connect($server,$user,$password,$db);
if(isset($_POST['login']))
{
    $name = $_POST['name'];
    $ids = $_POST['ids'];
    $data = mysqli_query($con,"SELECT * from users where user='$name' and id=$ids");
    if($data==true){
        $_SESSION['name'] = $name;
        $_SESSION['ids'] = $ids;
    }
 }   
 ?>

 <?php 
if(!isset($_SESSION['name']))
 {   
  ?>
<form  method="POST">
    <input type="text" id="name" name="name" placeholder="Name">
    <input type="text" id="ids" name="ids" placeholder="ID">
    <br>
    <input type="submit" value="login" id="loginbtn" name="login">
</form>
<?php } else{ 
    $data = mysqli_query($con,"SELECT * from users where id!={$_SESSION['ids']}");
    while($res = mysqli_fetch_array($data))
    {
        ?>
        <p><a href="?rid=<?php echo $res['id'] ?>"><?php echo $res['user'] ?></a></p>
        <?php  
    }
 ?>
<form id="web_socket_form" method="POST">
    <input type="text" id="msg" name="msg">
    <br>
    <input type="submit" value="send" id="send_btn" name="send">
    <br>
    <a href="lo.php">logout</a>
</form>
<div id="msgBox">
    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    $.ajax({
    url:"send.php",
    type: "POST",
    data :{
    fetch:"fetch",
    ids:<?php echo $_SESSION['ids']; ?>,
    rid:<?php echo $_GET['rid'] ?>
    },
    success:function(data){
    jQuery('#msgBox').html(data);
    }
    });
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    $.ajax({
    url:"send.php",
    type: "POST",
    data :{
    fetch:"fetch",
    ids:<?php echo $_SESSION['ids']; ?>,
    rid:<?php echo $_GET['rid'] ?>
    },
    success:function(data){
    jQuery('#msgBox').html(data);
    }
    });
    var msgData = jQuery.parseJSON(e.data);
    console.log(e.data);
    var html="<p>"+msgData.name+"</p> : "+msgData.msg+"</br>";
    jQuery('#msgBox').html(html);
};
jQuery('#send_btn').click(function(e){
    var msg = jQuery('#msg').val();
    var name = "<?php echo $_SESSION['name']; ?>";
    var ids = "<?php echo $_SESSION['ids']; ?>";
    var rid = "<?php echo $_GET['rid']; ?>";
    var content={
        msg: msg,
        name:name,
        ids:ids,
        rid:rid
    };
    $.ajax({
    url:"send.php",
    type: "POST",
    data :{
    msg:msg,
    name:name,
    ids:ids,
    rid:rid
    },
    success:function(data){
        $.ajax({
    url:"send.php",
    type: "POST",
    data :{
    fetch:"fetch",
    ids:<?php echo $_SESSION['ids']; ?>,
    rid:<?php echo $_GET['rid'] ?>
    },
    success:function(data){
    jQuery('#msgBox').html(data);
    }
    });
    }
    });
    conn.send(JSON.stringify(content));
e.preventDefault();
});
</script>
<?php  } ?>