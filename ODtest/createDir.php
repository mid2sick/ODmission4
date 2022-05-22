<?php
    if(isset($_POST['createDir'])) {
        createDir();
    }

    function createDir() {
        // get the current user's name and the new directory's name 
        $dirName = $_POST['newDir'];
        $username = $_SESSION['username'];

        // if the directory name is empty, return error
        if (empty($dirName)) {
            $_SESSION['uploadSuccess'] = "Error: Directory name cannot be empty.<br>";
            return;
        }

        $db = mysqli_connect('localhost', 'root', '', 'loginPage') or die("Connect failed: %s\n". $db -> error);        

        // insert the new directory into the `directory` table
        $query = "INSERT INTO `directory`(`dirName`, `username`,`metadata`) VALUES ('$dirName','$username','[]')";
        mysqli_query($db, $query);
        
        // get the new directory's id
        $query = "SELECT directory.dirID FROM `directory` INNER JOIN (SELECT username, MAX(dirID) AS most_recent_dirID FROM `directory` GROUP BY username) tmpTable ON directory.username = '$username' AND directory.dirID = most_recent_dirID WHERE 1";
        $result = mysqli_query($db, $query);
        $curID = mysqli_fetch_assoc($result);
        $curID = $curID["dirID"];

        // return if the edit successed                
        $_SESSION['uploadSuccess'] = "Directory successfully created.<br>";
    }

    
?>
