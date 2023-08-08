
<!DOCTYPE html>
<html>
  <head>
    <title>Chat</title>
    <?php 
    include('link.php');include('style.php');include('chatDB.php');
    if (!isset($_SESSION['user_Id'])) {
    ?>
    <script>
      alert("Logged in");
      window.location.href="login.php";
    </script>
    <?php
   }
     ?>
  </head>
  <?php

if (isset($_GET['addfid'])) {
  $msgs = "";
include 'chatDB.php';
   $sid = $_SESSION['user_Id'];
   $uid = $_GET['addfid'];
   $sql = "select * from friends where session_id='$sid' and to_user_id='$uid' and status=0";
   $query = mysqli_query($con,$sql);
   $count = mysqli_num_rows($query);
   if ($count==1) {
     $msgs = "Already added";
   }else{
    $data1 = mysqli_fetch_array(mysqli_query($con,"select * from users where id='$sid'"));
    $data2 = mysqli_fetch_array(mysqli_query($con,"select * from users where id='$uid'"));
    $sUser = $data1['user'];
    $toUser = $data2['user'];
    $toimg = $data2['image'];
   $sql = mysqli_query($con,"insert into friends(session_users, to_user,session_id,to_user_id,image) values('$sUser','$toUser','$sid','$uid','$toimg')");
}
}
 ?>
  <body>
    <div class="container-fluid h-100">
      <div class="row justify-content-center h-100">
        <div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
          <h5 class="text-center text-danger"><?php if(isset($msgs)){ echo $msgs;} ?></h5>
          <div class="card-header">
            <div class="input-group">
              <span><a href="index.php" class="btn btn-primary mx-2">Back</a> </span>
              <input type="text" onkeyup="searchresults(this.value)" id="" placeholder="Search..." name="" class="form-control search">
              <div class="input-group-prepend">
                <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
              </div>
            </div>
          </div>
          <div class="card-body contacts_body">
            <ui class="contacts" id="livesearch">
              <?php
                $users = mysqli_query($con,"SELECT * FROM users WHERE id!={$_SESSION['user_Id']}") or die(mysqli_error($con));
                $class = "";
                while($res = mysqli_fetch_assoc($users))
                {
                if($res['status'] == 1)
                {
                $class = "online_icon";
                }
                else{
                $class = "offline_icon";
                }
                ?>
                <li class="active">
                  <div class="d-flex bd-highlight">
                    <div class="img_cont">
                      <img src="uploads/<?php echo $res['image'] ?>" class="rounded-circle user_img">
                      <span class="<?php echo  $class ?>"></span>
                    </div>
                    <div class="user_info">
                      <span><?php echo $res['user'] ?></span>
                      <p><?php echo $res['email'] ?></p>
                      <a href="?addfid=<?php echo $res['id'] ?>" class="btn btn-primary btn-sm mx-2">Make a friend</a>
                    </div>
                  </div>
                </li>
                <?php
                }
                ?>
            </ui>
          </div>
          <div class="card-footer"></div>
        </div></div>
      </div>
    </div>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
        $(document).ready(function(){
$('#action_menu_btn').click(function(){
  $('.action_menu').toggle();
});
  });
    </script>
    <script type="text/javascript">
    function searchresults(str) {
        if (str.length==0) {
    document.getElementById("livesearch").innerHTML='<?php
$users = mysqli_query($con,"SELECT * FROM users WHERE id!={$_SESSION['user_Id']}") or die(mysqli_error($con));
$class = "";
while($res = mysqli_fetch_assoc($users))
{
if($res['status'] == 1)
{
$class = "online_icon";
}
else{
$class = "offline_icon";
}
?>
<li class="active"><div class="d-flex bd-highlight"><div class="img_cont"><img src="uploads/<?php echo $res['image'] ?>" class="rounded-circle user_img"><span class="<?php echo  $class ?>"></span></div><div class="user_info"><span><?php echo $res['user'] ?></span><p><?php echo $res['email'] ?></p><a href="?addfid=<?php echo $res['id'] ?>" class="btn btn-primary btn-sm mx-2">Make a friend</a></div></div></li><?php
}
?>';
    return;
  }
        $.ajax({
            url:"chat_mailer.php",
            type: "POST",
            data :{
              query:str,
              type:"newfriends"
            },
            success:function(data){
                $('#livesearch').html(data);
            }
        });
    }
</script>
  </body>
</html>