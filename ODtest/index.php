<?php 
	session_start(); 
	require_once('upload.php');
	require_once('createDir.php');
	if (!isset($_SESSION['username'])) {
		$_SESSION['msg']="You must log in first";
		header('location: registration/login.php');
	}
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: registration/login.php");
	}
	$username= $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
		<link rel="stylesheet" type="text/css" href="registration/style.css">
		<link rel="stylesheet" type="text/css" href="index.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type="text/javascript" src="updateDirList.js"></script>
		<script type="text/javascript" src="openDir.js"></script>

	</head>
	<body>

		<div class="header">
			<h2>Home Page</h2>
		</div>
		<div class="content">
			<div class="leftBox">
				<!-- notification message -->
				<?php if (isset($_SESSION['success'])) : ?>
				<div class="error success" >
					<h3>
					<?php 
						echo $_SESSION['success']; 
						unset($_SESSION['success']);
					?>
					</h3>
				</div>
				<?php endif ?>
				<?php if (isset($_SESSION['uploadSuccess'])) :?>
				<div class="error success" >
					<h3>
					<?php 
						echo $_SESSION['uploadSuccess']; 
						unset($_SESSION['uploadSuccess']);
					?>
					</h3>
				</div>
				<?php endif ?>
				<!-- logged in user information -->
				<?php  if (isset($_SESSION['username'])) : ?>
					<div class="personalBox">
						<p>User <strong><?php echo $_SESSION['username']; ?></strong></p>
						<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
					</div>
				<?php endif ?>
				<?php
					echo "<div>".$_SESSION['uploadSuccess']."</div>";
				?>
				<!-- update dir list -->
				<div>
					<!-- 
					<div class="button">
						<input type="button" onclick="updateDirList('theSessionName')" value="update dirList">  
					</div>
					-->
					<div>
						<form class="createDirBox" method="post">
							<input type="text" name="newDir" id="newDir" class="newDirBox">
							<input type="submit" class="newDirBtn" value="create Directory" name="createDir">
						</form>
						
					</div>
					<div id="dirList">
						<!-- where the directory list will show up at -->
					</div>
				</div>
				
			</div>
			<div class="rightBox">
				<!-- upload csv file -->
				<form class="uploadBox" method="post" enctype="multipart/form-data">
					<div>
						Select a csv file to upload:
					</div>
					<div>
						<input type="file" name="fileToUpload" id="fileToUpload">
						<input type="submit" class="uploadBtn" value="Upload File" name="submitCSV">
					</div>
				</form>
				<!-- show the metadata -->
				<div id="metaList">
					<!-- where the metadata wiil be put at -->
				</div>
			</div>
		</div>
		<script>
			var usr = '<?php echo $username; ?>';
			updateDirList(usr);
		</script>
	</body>
</html>