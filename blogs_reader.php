<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Write Blogs</title>
	<?php include'nav.php'; ?>
</head>
<body>
	<style type="text/css">
		/* width */
::-webkit-scrollbar {
  width: 20px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #b09a9a; 
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #b30000; 
}
div.msg_card_body {
  height: 80vh;
  overflow: scroll;
}
	</style>
	<?php 
	if(isset($_GET['slug-id']))
	{
           ?>
           <div class="container-fluid mt-4 mb-4">
            <a href="your_blogs.php">Back</a>
    <div class="row justify-content-md-center">
        <div class="col-md-6 col-lg-9" id="main_contents">
            $bid = $_POST['slug_id'];
            $bsqls = mysqli_query($con,"SELECT * FROM blogger where bid='$bid'");
            $blogs  = mysqli_fetch_array($bsqls);
            if($blogs==true){
            $lc = "far";
            if(isset($_COOKIE['like_'.$blogs['id']]))
            {
              $lc = "fas";
            }

            $dc = "far";
            if(isset($_COOKIE['dislike_'.$blogs['id']]))
            {
              $dc = "fas";
            }
             ?>
            <div class="card text-dark">
            <h1 class="text-center"><?php echo $blogs['blogs_topic'] ?></h1>
            <h3 class="text-center"><?php echo $blogs['blogs_area'] ?></h3>
            </div>

            <div class="card text-dark mt-4">
            <div class="container">
            <p  style="color:#000;"><?php echo $blogs['content'] ?></p>
            </div>
            </div>
            <button class="btn btn-primary btn-sm" onclick="likes(<?php echo $blogs['id'] ?>)"><i class="<?php echo $lc ?> fa-thumbs-up" id="like"></i> <span id="likes">(<?php echo $blogs['likes'] ?>)</span></button>

            <button class="btn btn-primary btn-sm" onclick="dislike(<?php echo $blogs['id'] ?>)"><i class="'.$dc.' fa-thumbs-down" id="dislike1"></i> <span id="dislike">(<?php echo $blogs['dislike'] ?>)</span></button>
        </div>


        <div class="col-md-6 col-lg-3">
        <div class="card">
        <div class="card-header">
        <h3>Advertisement </h3>
        </div>
        </div>
        <div class="msg_card_body">
        <?php 
        $type = $_GET['type'];
        $adv = mysqli_query($con,"SELECT * FROM blogger");
        while($row = mysqli_fetch_array($adv))
        {
         ?>   
         
         	<div class="card mt-2">
         <div class="card-header fw-bold">
         	<?php echo $row['blogs_topic'] ?>
         </div>
         <a href="javascript:void(0)" onclick="main_content(<?php echo $row['id'] ?>)">
        <div class="card-body">
        	<img src="images/<?php echo $row['image'] ?>" class="img-fluid">
        </div>
         </a>
        </div>
         <?php 
          }
          ?>
           </div>
        </div>


    </div>
</div>
<?php
	}else{
		echo "<h2 style='text-align: center;margin-top: 100px;'>Something went wrong !</h2>";
	}


	 ?>
	 <script type="text/javascript">
  function likes(lid){
        jQuery.ajax({
      url:'adder.php',
      type:'post',
      data :{
        type:'likes',
        lid:lid
        },
        success:function(result) {
        var l = jQuery.parseJSON(result);
         if(l.error == 'no' && l.operation=='like'){
        jQuery('#like').removeClass('far');
        jQuery('#like').addClass('fas');
        jQuery('#dislike1').addClass('far');
        jQuery('#dislike1').removeClass('fas');
        }
        else if(l.error == 'no' && l.operation=='unlike'){
        jQuery('#like').addClass('far');
        jQuery('#like').removeClass('fas');
        jQuery('#dislike1').addClass('fas');
        jQuery('#dislike1').removeClass('far');
        }
        jQuery('#likes').html(l.likes);
        jQuery('#dislike').html(l.dislike);
        }
    });
  }

  function dislike(lid){
        jQuery.ajax({
      url:'adder.php',
      type:'post',
      data :{
        type:'dislike',
        lid:lid
        },
        success:function(result) {
        var l = jQuery.parseJSON(result);
         if(l.error == 'no' && l.operation=='dislike'){
        jQuery('#dislike1').removeClass('far');
        jQuery('#dislike1').addClass('fas');
        jQuery('#like').addClass('far');
        jQuery('#like').removeClass('fas');
        }
        else if(l.error == 'no' && l.operation=='undislike')
        {
        jQuery('#dislike1').removeClass('fas');
        jQuery('#dislike1').addClass('far');
        jQuery('#like').addClass('fas');
        jQuery('#like').removeClass('far');
        }
        jQuery('#dislike').html(l.dislike);
        jQuery('#likes').html(l.likes);
        }
    });
  }

  function main_content(slug_id){
        jQuery.ajax({
      url:'tools.php',
      type:'post',
      data :{
        type:'main_content',
        slug_id:slug_id
        },
        success:function(results) {
        	jQuery('#main_contents').html(results);
        }
    });
    }
</script>
<?php include'footer.php'; ?>
</body>
</html>