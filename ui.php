<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>UI</title>
	<?php 
	include 'link.php';
	include 'chatDB.php';

	$Data = mysqli_query($con,"SELECT * from message WHERE (From_User='21' AND To_User='20') OR (From_User='20' AND To_User='21') ");
	?>
</head>
<body>
	<style>
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
</style>
<div class="container">
	<div class="row">
		<div class="col-lg-4">
			<div class="card">
				<div class="card-header">
					<h3>Your Friends</h3>
				</div>
				<div class="card-body">
					<div class="overflow-scroll">
						
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-8">
			<div class="card-body">
					<div  style="overflow:scroll; height:400px;">
						<?php 
						while($row = mysqli_fetch_array($Data)){
							if ($row['From_User'] == 20) {
								?>
								<div class="d-flex justify-content-between">
					              <p class="small mb-1 fw-bold">Timona Siera</p>
					            </div>
					            <div class="d-flex flex-row justify-content-start">
					              <img src="uploads/<?php echo $row['session_img']?>"
					                alt="avatar 1" style="width: 45px; height: 100%;border-radius:50%">
					              <div>
					                <p class="fw-bold p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;"><?php echo $row['message']; ?></p>
					                <p class="small mb-1 text-muted fw-bold">23 Jan 2:00 pm</p>
					              </div>
					            </div>
								<?php
							}else{
								?>
					            <div class="d-flex flex-row justify-content-end mb-4 pt-1">
					              <div>
					              	<p class="small mb-1 fw-bold">Johny Bullock</p>
					                <p class="fw-bold p-2 me-3 mb-3 text-white rounded-3 bg-warning"><?php echo $row['message'] ?></p>
					                <p class="small mb-1 text-muted fw-bold">23 Jan 2:00 pm</p>
					              </div>
					              <img src="uploads/<?php echo $row['session_img']?>"
					                alt="avatar 1" style="width: 45px; height: 100%;border-radius:50%">
					            </div>
								<?php
							}
						}
						?>
					</div>
			</div>
		</div>
	</div>
</div>


<?php include 'footer.php'?>
</body>
</html>