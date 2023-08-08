
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
            <h5>REGISTER PANNEL</h5>
          </div>
          <div class="card-body contacts_body">
            <form method="POST">
              <ui class="contacts">
                <li class="active">
                  <div class="input-group">
                    <input type="text"  id="" placeholder="Name" name="user" class="form-control "> 
                  </div>
                </li>
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
                    <input type="submit"  value="Register Now" name="register" class="form-control"> 
                  </div>
                </li>

                <li class="active text-center">
                    <a href="login.php">Login Now</a>
                </li>
            </ui>
            </form>
          </div>
          <div class="card-footer"></div>
        </div></div>
      </div>
    </div>

 <?php 
if (isset($_POST['register'])) {
  $user = $_POST['user'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];

  $msq = mysqli_query($con,"select * from users where email='$email' and password='$pass'") or die();
  $count = mysqli_num_rows($msq);
  if($count>0)
  {
    ?>
    <script>
      alert("User already registered");
    </script>
    <?php
  }else{
    $insert = mysqli_query($con,"insert into users(user,email,password,image)values('$user','$email','$pass','user.png')");
    ?>
    <script>
      alert("User registered");
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