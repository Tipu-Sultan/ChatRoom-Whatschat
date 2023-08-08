<?php 
session_start();       


include 'chatDB.php';
if(isset($_POST['from_User'])){
$from_User = $_POST['from_User'];
$to_User = $_POST['to_User'];

$chats = mysqli_query($con,"SELECT * from message WHERE (From_User='".$from_User."' AND To_User='".$to_User."') OR (From_User='".$to_User."' AND To_User='".$from_User."') ") 
or die("Failed to query database".mysqli_error());

$read_up = mysqli_query($con,"update friends set unread=0 WHERE session_id =$from_User and to_user_id =$to_User ");
$targetDir = "chatfiles/";
while($chat = mysqli_fetch_assoc($chats))
{
$oldtime = $chat['chat_time'];

$time = time(); 
echo '<br>';
$count = 0;
$diff = $time - $oldtime;
$suffix="";
switch(1)
{
case ($diff < 60):       //calculate seconds
$count=$diff;
if($count==0)
$count="sec";
elseif($count==1)
$suffix="sec";
else
$suffix="secs";
break;

case ($diff > 60 && $diff < 3600): //calculate minutes
$count=floor($diff/60);
if($count==1)
$suffix="mi";
else
$suffix="mi";
break;

case ($diff > 3600 && $diff < 86400):   //calculate hours
$count=floor($diff/3600 );
if($count==1)
$suffix="h";
else
$suffix="h";
break;

case ($diff>86400): //calculate days
$count=floor($diff/86400);
if($count==1)
$suffix="d";
else
$suffix="d";
break;
}
$chat_time=$lseen=$count." ".$suffix." ago ";
$filenames = "chatfiles/{$chat['message']}";  
$newName = $chat['message'];
$allowTypes = array('mp4');
$allowTypes1 = array('jpg', 'jpeg','png','gif');
$targetFilePath = $targetDir.$newName;
$targetFilePath1 = $targetDir.$newName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
$fileType1 = pathinfo($targetFilePath1, PATHINFO_EXTENSION);                
                      if ($chat['From_User'] == $from_User) {
                        $_SESSION['time'] = $chat['chat_time'];

                        ?>
                        <div class="d-flex justify-content-end mb-4">
                          <div class="msg_cotainer_send">
                            <?php 
                              if(in_array($fileType, $allowTypes)){
                              ?>
                              <a href="javascript:void(0)" id="remove_item" onclick="remove_item(<?php echo $chat['id']; ?>)" style="text-decoration: none;" class="btn btn-danger btn-sm mt-3"><i class="fa fa-trash"></i></a>
                              <video width="250" height="125" controls="">
                                <source src="chatfiles/<?php echo $chat['message'] ?>" type="video/mp4">
                              </video>
                              <?php
                            }else if(in_array($fileType1, $allowTypes1)){
                              ?>
                              <a href="javascript:void(0)" id="remove_item" onclick="remove_item(<?php echo $chat['id']; ?>)" style="text-decoration: none;" class="btn btn-danger btn-sm mt-3"><i class="fa fa-trash"></i></a>
                              <a href="chatfiles/<?php echo $chat['message'] ?>">
                              <img src="chatfiles/<?php echo $chat['message'] ?>" class="rounded img-fluid" width="250" height="125">
                              </a>
                              <?php
                            }else
                            {
                              ?>
                              <a href="javascript:void(0)" id="remove_item" onclick="remove_item(<?php echo $chat['id']; ?>)" title="delete this message" style="text-decoration: none;">
                                <p class="fw-bold"><?php echo $chat['message']; ?></p>
                                </a>
                              <?php

                            }

                             ?>
                          </a>
                            <span class="msg_time_send fw-bold"><?php echo $chat_time ?></span>
                          </div>
                          <div class="img_cont_msg">
                            <img src="uploads/<?php echo $chat['session_img'] ?>" class="rounded-circle user_img_msg">
                          </div>
                        </div>
                        <?php
                      }else{
                        ?>
                        <div class="d-flex justify-content-start mb-4">
                          <div class="img_cont_msg">
                            <img src="uploads/<?php echo $chat['session_img'] ?>" class="rounded-circle user_img_msg">
                          </div>
                          <div class="msg_cotainer">
                            <?php 
                            if (file_exists($filenames)) {
                              if(in_array($fileType, $allowTypes)){
                              ?>
                    
                              <video width="250" height="125" controls="">
                                <source src="chatfiles/<?php echo $chat['message'] ?>" type="video/mp4">
                              </video>
                              <?php
                            }else{
                              ?>
                              <a href="chatfiles/<?php echo $chat['message'] ?>" style="text-decoration: none;" class="btn btn-danger btn-sm mt-3" download=""><i class="fa fa-download"></i></a>
                              <a href="chatfiles/<?php echo $chat['message'] ?>">
                              <img src="chatfiles/<?php echo $chat['message'] ?>" class="rounded img-fluid" width="250" height="125">
                              </a>
                              <?php
                            }
                            }else
                            {
                              ?>
                              <p class="fw-bold"><?php echo $chat['message']; ?></p>
                              <?php
                            }

                             ?>
                            <span class="msg_time fw-bold"><?php echo $chat_time ?></span>
                          </div>
                        </div>
                        <?php
                      }
                    }
               
          }    
?>
