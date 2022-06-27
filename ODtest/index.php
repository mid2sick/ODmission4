<?php 
	session_start(); 
	// require_once('upload.php');
	// require_once('createDir.php');
	require_once('server.php');
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
				<?php  if (isset($_SESSION['username'])) : ?>
					<div class="personalBox">
						<p>User <strong><?php echo $_SESSION['username']; ?></strong></p>
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
						<label for="fileSource">資料來源：</label>
						<select name="fileSource" id="fileSource">
							<option value="AHCMS">AHCMS 國史館檔案史料文物查詢系統</option>
							<option value="AHTWH">AHTWH 國史館臺灣文獻館典藏管理系統</option>
							<option value="NDAP">NDAP 臺灣省議會史料總庫</option>
							<option value="TLCDA">TLCDA 地方議會議事錄</option>
						</select>
						<input type="file" name="fileToUpload" id="fileToUpload">
						<input type="submit" class="uploadBtn" value="Upload File" name="submitCSV">
					</div>
				</form>
				<!-- show the metadata -->
				<form class="button rmDirBtn" action="" method="post">
					<button name="removeDir" class="" value="" style="height:40px;z-index:100;position:relative">移除當前資料夾</button>
				</form>
				<div id="metaList" style="z-index: 90">
					<!-- where the metadata wiil be put at -->
					<?php
						if(isset($_SESSION['listResult'])) {
							echo "In directory: ".$_SESSION['currentDir'].'<br><br>';
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
			var usr = '<?php echo $username; ?>';
			updateDirList(usr);
		</script>
		-->
	</body>
</html>