<?php 
	session_start(); 
	require_once('upload.php');
	// require_once('createDir.php');
	require_once('server.php');
	if (!isset($_SESSION['userID'])) {
		$_SESSION['msg']="You must log in first";
		header('location: registration/login.php');
	}
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['userID']);
		header("location: registration/login.php");
	}
	$userID= $_SESSION['userID'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
		<link rel="stylesheet" type="text/css" href="registration/style.css">
		<link rel="stylesheet" type="text/css" href="index.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<!--
		<script type="text/javascript" src="updateDirList.js"></script>
		<script type="text/javascript" src="openDir.js"></script>
		-->
	</head>
	<body>

		<div class="header">
			<h2>Home Page</h2>
		</div>
		<div class="content">
			<div class="leftBox">
				<!-- notification message -->
				<?php if (isset($_SESSION['msg'])) : ?>
				<div class="error success" >
					<h3>
					<?php 
						echo $_SESSION['msg']; 
						unset($_SESSION['msg']);
					?>
					</h3>
				</div>
				<?php endif ?>
				<?php if (isset($_SESSION['uploadMsg'])) :?>
				<div class="error success" >
					<h3>
					<?php 
						echo $_SESSION['uploadMsg']; 
						unset($_SESSION['uploadMsg']);
					?>
					</h3>
				</div>
				<?php endif ?>
				<!-- logged in user information -->
				<?php  if (isset($_SESSION['userID'])) : ?>
					<div class="personalBox">
						<p>User <strong><?php echo $_SESSION['userID']; ?></strong></p>
						<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
					</div>
				<?php endif ?>
				<?php
					echo "<div>".$_SESSION['uploadMsg']."</div>";
				?>
				<!-- update dir list -->
				<div>
					<div>
						<form class="createDirBox myForm" method="post" action="">		<!-- If action is set to "" or if the action attribute is missing, the form submits to itself. -->
							<input type="text" name="newDir" id="newDir" class="newDirBox">
							<input type="submit" class="newDirBtn" value="create Directory" name="createDir">
						</form>
						
					</div>
					<?php if (isset($_SESSION['dirList'])) : ?>
					<div class="" >
						<h3>
						<?php include_once('updateDir.php'); ?>
						</h3>
					</div>
					<?php endif ?>
					<!--
					<div id="dirList">
						 where the directory list will show up at 
					</div>
					-->
				</div>
				
			</div>
			<div class="rightBox">
				<!-- upload csv file -->
				<form class="uploadBox myForm" method="post" enctype="multipart/form-data">
					<div>
						Select a csv file to upload:
					</div>
					<div>
						<input type="file" name="fileToUpload" id="fileToUpload">
						<input type="submit" class="uploadBtn" value="Upload File" name="submitCSV">
					</div>
				</form>
				<!-- show the metadata -->
				<div>
					<form class="button rmDirBtn" action="" method="post">
						<button name="removeDir" value="">移除當前資料夾</button>
					</form>
				</div>
				<div id="metaList">
					<!-- where the metadata wiil be put at -->
					<?php
						if(isset($_SESSION['listResult'])) {
							var_dump($_SESSION['listResult']); 
						} else {
							echo "Please select a directory";
						}
					?>
				</div>
			</div>
		</div>
		<!--
		<script>
			var usr = '<?php echo $userID; ?>';
			updateDirList(usr);
		</script>
		-->
	</body>
</html>