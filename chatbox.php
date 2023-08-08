
<!DOCTYPE html>
<html>
  <head>
    <title>ChatRoom</title>
    <?php 
    include('link.php'); include('style.php');include('chatDB.php');
    if (!isset($_SESSION['user_Id'])) {
    ?>
    <script>
      window.location.href="login.php";
    </script>
    <?php
   }
   $sid = $_SESSION['user_Id'];
   $fetch1 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM users WHERE id=$sid"));
   $imgID = $fetch1['image'];
   $user = $fetch1['user'];
     ?>
  </head>
  <body>
    <div class="container-fluid h-100">
      <div class="row justify-content-center h-100">
        <div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
          <div class="card-header">
            <div class="mb-3">
           <h5 class="float-left"><a href="add_friend.php" class="btn btn-primary btn-sm mt-2">Add new friends</a></h5>
           <h5 class="float-left"><?php echo $user ?></h5>
           <img src="uploads/<?php echo $imgID ?>" id="action_menu_btn1"  class="rounded-circle float-right" width="50" height="50">
             <div class="action_menu1">
                <ul>
                  <li><a href="logout.php" style="text-decoration: none;"><i class="fas fa-user-circle"></i> Logout</a></li>
                  <li data-mdb-toggle="modal" data-mdb-target="#myModaledit"><i class="fas fa-users"></i> Change profile</li>
                  <li><i class="fas fa-plus"></i> Add to group</li>
                </ul>
              </div>
              </div>
              <div class="input-group mt-3 ">
              <input type="text" placeholder="Search..." onkeyup="searchresults(this.value)" name="" class="form-control search">
              <div class="input-group-prepend">
                <a href="chatbox.php" style="text-decoration: none;"><span class="input-group-text search_btn">Refresh</span></a>
              </div>
              </div>
          </div>
          <div class="card-body contacts_body">
            <center>
                <div>
                  <video id="vid"></video>
                </div>
              </center>
            <ui class="contacts" id="livesearch">
              <?php 
              $sid = $_SESSION['user_Id'];
          $existfriends = mysqli_query($con,"SELECT * FROM friends WHERE session_id =$sid and status=0") or die(mysqli_error($con));

          // $class = "";
           while($res = mysqli_fetch_assoc($existfriends))
           {
            
            if($res['last_seen'] == "online")
            {
              $class = "online_icon";
            }
            else{
              $class = "offline_icon";
            }
            ?>
            <a href="?to_User=<?php echo $res['to_user_id'] ?>"style="text-decoration: none;">
              <li class="active">
              <div class="d-flex bd-highlight">
                <div class="img_cont">
                  <img src="uploads/<?php echo $res['image'] ?>" class="rounded-circle user_img">
                  <span class="<?php echo $class ?>"><span style="text-decoration: none;color: #fff;margin-left:0.2rem;"><?php echo $res['unread'] ?></span></span>
                </div>
                <div class="user_info">
                  <span><?php echo $res['to_user'] ?></span>
                  <p><?php echo $res['last_seen'] ?></p>
                  <p><?php echo $res['last_msg'] ?></p>
                </div>
              </div>
            </li>
            </a>
            <?php
          }
          ?>
            </ui>
          </div>
          <div class="card-footer"></div>
        </div></div>
        <div class="col-md-8 col-xl-6 chat">
          <div class="card">
            <div class="card-header msg_head">
              <div class="d-flex bd-highlight">
                <?php  
              if (isset($_GET['to_User']))
              {
                  $userName = mysqli_query($con,"SELECT * from users where id='".$_GET['to_User']."' ") 
                  or die("Failed to query database".mysql_error());
                  $data1 = mysqli_fetch_assoc($userName);

                  if($data1['status'] == 1)
                  {
                    $class = "online_icon";
                  }
                  else{
                    $class = "offline_icon";
                  }
                ?>
                <div class="img_cont">
                  <img src="uploads/<?php echo $data1['image'] ?>" class="rounded-circle user_img">
                  <span class="<?php echo $class; ?>"></span>
                </div>
                <div class="user_info">
                  <span>Chat with <?php echo $data1['user'] ?></span>
                  <p id="countmsg"></p>
                </div>
                <div class="video_cam">
                  <span><i class="fas fa-video" id="but" autoplay></i></span>
                  <span><i class="fas fa-phone"></i></span>
                </div>
                <?php
              }
              ?>
              </div>
              <?php 
              if (isset($_GET['to_User']))
              {
              ?>
              <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
              <?php
            }
               ?>
              <div class="action_menu">
                <ul>
                  <li><i class="fas fa-plus"></i> Add to group</li>
                  <li><a href="?BlockUsers=<?php echo $_GET['to_User']; ?>" style="text-decoration: none;"><i class="fas fa-ban"></i> Block</a></li>
                </ul>
              </div>
            </div>
            <?php 
           if (isset($_GET['to_User']))
              {
           ?>
           <input type="text" id="from_User" name="from_User" value="<?php echo $_SESSION['user_Id']; ?>" hidden="true" />
           <input type="text" id="to_User" name="to_User" value="<?php echo $_GET['to_User']; ?>" hidden="true" />
            <div class="card-body msg_card_body" id="chat-content">

            </div>
                <?php
              }else{
                ?>
            <div class="card-body msg_card_body chat-content">
              <h4 class="text-center text-info mt-5">Start chats with your friends</h4>
              <center>
                <img src="uploads/chatimg.webp" width="400" height="200">
              </center>
            </div>
                <?php
              }
              ?>
              <style type="text/css">
                .image-upload>input {
                  display: none;
                }
              </style>
              <form id="messagef" method="post" enctype="multipart/form-data">
            <div class="card-footer">
              <div class="input-group">
                <label for="file-inputs">
                <div class="input-group-append image-upload">
                  <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                  <input id="file-inputs" type="file" name="file-inputs" />
                </div>
                </label>
                <input type="text" id="from_User" name="from_User" value="<?php echo $_SESSION['user_Id']; ?>" hidden="true" />
                <input type="text" id="to_User" name="to_User" value="<?php echo $_GET['to_User']; ?>" hidden="true" />
                <input name="message" id="message" class="form-control type_msg" placeholder="Type your message..." ></input>
                <div class="input-group-append">
                  <button class="input-group-text send_btn" id="submit"><i class="fas fa-location-arrow"></i></button>
                </div>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
$('#action_menu_btn').click(function(){
  $('.action_menu').toggle();
});
  });

        $(document).ready(function(){
$('#action_menu_btn1').click(function(){
  $('.action_menu1').toggle();
});
  });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<?php 
           if (isset($_GET['to_User']))
              {
           ?>
  <script type="text/javascript">
    function remove_item(id){
        jQuery.ajax({
      url:'chat_mailer.php',
      type:'post',
      data:'type=remove_item&msgid='+id,
      success:function(result){
        

      }
    });
  }
    $(document).ready(function(){


        jQuery('#messagef').on('submit',function(e){
        var vidFileLength = $("#file-inputs")[0].files.length;
        if(vidFileLength === 0){  
        jQuery.ajax({
            type: 'POST',
            url: 'chat_mailer.php',
            data:
            {
              from_User: $("#from_User").val(),
              to_User: $("#to_User").val(),
              message: $("#message").val()            },
        success: function(data)
        {
          jQuery('#chat-content').animate({scrollTop:1000000},800);
          $("#message").html(data);
          jQuery('#messagef')[0].reset();
          
        }
      });
      }else{
        jQuery.ajax({
        type: 'POST',
            url: 'chat_mailer.php',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
        success: function(data)
        {
          jQuery('#chat-content').animate({scrollTop:1000000},800);
          $("#message").html(data);
          jQuery('#messagef')[0].reset();
          
        }
      });
      }

        e.preventDefault();
      });
      

      setInterval(function(){
        jQuery.ajax({
          url:"fetch_chat.php",
          method: "POST",
          data:
          {
            from_User: $("#from_User").val(),
            to_User: $("#to_User").val()
          },
          dataType: "text",
          success: function(data)
          {
            $("#chat-content").html(data);
          }
        });
      },2000);

      setInterval(function(){
        jQuery.ajax({
          url:"chat_mailer.php",
          method: "POST",
          data:
          {
            to_User: $("#to_User").val(),
            countmsg:"countmsg"

          },
          dataType: "text",
          success: function(result)
          {
            $("#countmsg").html(result);
          }
        });
      },2000);

    });
  </script>
  <?php 
}
   ?>


   <script type="text/javascript">
    function searchresults(str) {
        if (str.length==0) {
    document.getElementById("livesearch").innerHTML='<?php 
          $existfriends = mysqli_query($con,"SELECT * FROM friends WHERE session_id ={$_SESSION['user_Id']}") or die(mysqli_error($con));
           while($res = mysqli_fetch_assoc($existfriends))
           {
            ?>
            <a href="?to_User=<?php echo $res['to_user_id'] ?>"><li class="active"><div class="d-flex bd-highlight"><div class="img_cont"><img src="uploads/<?php echo $res['image'] ?>" class="rounded-circle user_img"><span class=""></span></div><div class="user_info"><span><?php echo $res['to_user'] ?></span><p><?php echo $res['to_user'] ?> is online</p></div></div></li></a><?php
          }
          ?>';
    return;
  }
        $.ajax({
            url:"chat_mailer.php",
            type: "POST",
            data :{
              query:str,
              type:"added_friends"
            },
            success:function(data){
                $('#livesearch').html(data);
            }
        });
    }
</script>

<div class="modal fade" id="myModaledit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">CHANGE PROFILE PICTURE</h5>
        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">CHANGE PROFILE PICTURE</p>

        <form method="POST" action="chat_mailer.php" enctype="multipart/form-data">
          <div class="form-group">
            <input type="file" name="file" class="form-control">
            <input type="text" name="user_id" class="form-control" hidden="true" value="<?php echo $_SESSION['user_Id']; ?>">
            <input type="submit" class="btn btn-danger mt-3" id="changepic" name="changepic" value="Change Now">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
      document.addEventListener("DOMContentLoaded", () => {
        var but = document.getElementById("but");
        var video = document.getElementById("vid");
        var mediaDevices = navigator.mediaDevices;
        vid.muted = true;
        but.addEventListener("click", () => {
  
          // Accessing the user camera and video.
          mediaDevices
            .getUserMedia({
              video: true,
              audio: true,
            })
            .then((stream) => {
  
              // Changing the source of video to current stream.
              video.srcObject = stream;
              video.addEventListener("loadedmetadata", () => {
                video.play();
              });
            })
            .catch(alert);
        });
      });
    </script>
<?php 
if (isset($_GET['msg_del']) && isset($_GET['to_User'])) {
  $msg_del = $_GET['msg_del'];
  $query = mysqli_query($con,"delete from message where id='$msg_del'");
  ?>
  <script>
    window.location.href="chatbox.php?to_User=<?php echo $_GET['to_User']; ?>";
    jQuery('#chat-content').scrollBottom(jQuery('#chat-content')[0].scrollHeight);
  </script>
  <?php
}

if (isset($_GET['BlockUsers'])) {
  $uid = $_GET['BlockUsers'];
  $sid = $_SESSION['user_Id'];
  $query = mysqli_query($con,"DELETE FROM friends where session_id='$sid' and to_user_id=$uid");
  $query = mysqli_query($con,"DELETE FROM message where From_User='$sid' and To_User=$uid");
  ?>
  <script>
    window.location.href="chatbox.php";
    jQuery('#chat-content').scrollBottom(jQuery('#chat-content')[0].scrollHeight);
  </script>
  <?php
}

if (isset($_GET['deleteItem'])) {
  $imgID = $_GET['deleteItem'];
  $cid = $_GET['chatId'];
  $uid = $_GET['to_User'];
  $query = mysqli_query($con,"DELETE FROM message where id=$cid");
  unlink("chatfiles/"."{$imgID}");
  ?>
  <script>
    window.location.href="chatbox.php?to_User=<?php echo $uid ?>";
    jQuery('#chat-content').scrollBottom(jQuery('#chat-content')[0].scrollHeight);
  </script>
  <?php
}

    include('footer.php');
    ?>
  </body>
</html>
