
<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <?php 
    include('link.php'); include('style.php');include('chatDB.php');
    
     ?>
  </head>
 
  <body>
    <div class="container-fluid h-100">
      <div class="row justify-content-center h-100">
        <div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
          <h5 class="text-center text-danger"><?php if(isset($msgs)){ echo $msgs;} ?></h5>
          <div class="card-header">
            <h5>LOGIN PANNEL</h5>
          </div>
          <div class="card-body contacts_body">
            <form method="POST">
              <ui class="contacts">
                <li class="active">
                  <div class="input-group">
                    <input type="text"  id="" placeholder="Email" name="email" class="form-control "> 
                  </div>
                </li>
                <li class="active">
                  <div class="input-group">
                    <input type="password"  id="" placeholder="Password" name="pass" class="form-control"> 
                  </div>
                </li>
                <li class="active">
                  <div class="input-group">
                    <input type="submit"  value="Login Now" name="login" class="form-control"> 
                  </div>
                </li>

                <li class="active text-center">
                    <a href="register.php">Create a new account</a>
                </li>
            </ui>
            </form>
          </div>
          <div class="card-footer"></div>
        </div></div>
      </div>
    </div>

 
<?php 
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $pass = $_POST['pass'];

  $msq = mysqli_query($con,"select * from users where email='$email' and password='$pass'") or die();
  $count = mysqli_num_rows($msq);
  if($count>0)
  {
    $email_pass = mysqli_fetch_array($msq);
    $_SESSION['user'] = $email_pass['user'];
       $uid= $_SESSION['user_Id'] = $email_pass['id'];
       $_SESSION['uid'] = $email_pass['id'];
       $_SESSION['email'] = $email_pass['email'];
       $_SESSION['password'] = $email_pass['password'];
       $_SESSION['sid'] = $email_pass['status'];
       $_SESSION['image'] = $email_pass['image'];
       $update = mysqli_query($con,"update users set status=1 where id=$uid");
       $update1 = mysqli_query($con,"update friends set last_seen='online' where to_user_id=$uid");
    ?>
    <script>
      alert("Logged in");
      window.location.href="index.php";
    </script>
    <?php
  }
}
 ?>

<script type="text/javascript">
        $(document).ready(function(){
$('#action_menu_btn').click(function(){
  $('.action_menu').toggle();
});
  });
    </script>
    
  </body>
</html>