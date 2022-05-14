<?php
    if(isset($_POST['createDir'])) {
        createDir();
    }

    function createDir() {
        $db = mysqli_connect('localhost', 'root', '', 'loginPage') or die("Connect failed: %s\n". $db -> error);
        $dirName = $_POST['newDir'];
        $username = $_SESSION['username'];

        if (empty($dirName)) {
            $_SESSION['uploadSuccess'] = "Error: Directory name cannot be empty.<br>";
            return;
        }
        
        // query the user's directory id list and name list in the table `login`
        $queryID = "SELECT `directoryIDs` FROM `login` WHERE username='$username'";
        $queryName = "SELECT `directoryNames` FROM `login` WHERE username='$username'";

        $originalID = mysqli_query($db, $queryID);
        $originalName = mysqli_query($db, $queryName);
        
        $row = mysqli_fetch_assoc($originalID);
        $idArr = $row["directoryIDs"];
        
        $row = mysqli_fetch_assoc($originalName);
        $nameArr = $row["directoryNames"];

        
        // edit the directoryID list in the table `login`
        // and insert a new directory row in the table `directory`
        $idArr = editDirList($idArr, $db);
        
        // check if the original directoryName list is empty
        // before edit the directoryName list in the table `login`
        if ($nameArr != "[]") {
            $newNameList = str_replace(']', ',"'.$dirName.'"]', $nameArr);
        } else {
            $newNameList = str_replace(']', '"'.$dirName.'"]', $nameArr);
        }
        
        // edit the directory 
        $queryUserEdit = "UPDATE `login` SET `directoryIDs`='$idArr', `directoryNames`='$newNameList' WHERE `username`='$username'";
        mysqli_query($db, $queryUserEdit);
        $_SESSION['uploadSuccess'] = "Directory successfully created.<br>";
    }

    function editDirList($originArr, $db) {
        $queryDirCur = "SELECT `dirCurrentID` FROM `serverVar`";
        $dirCurResult = mysqli_query($db, $queryDirCur);
        $dirCurNum = mysqli_fetch_assoc($dirCurResult);
        $curId = $dirCurNum["dirCurrentID"];
        
        // edit table directory, insert the new directory
        $queryDirectory = "INSERT INTO `directory`(`dirID`, `documentIDs`) VALUES ('$curId','[]')";
        mysqli_query($db, $queryDirectory);

        if($originArr != "[]") {
            $originArr = str_replace("]", ",".$curId."]", $originArr);
        } else {
            $originArr = str_replace("]", $curId."]", $originArr);
        }
        
        // update table serverVar, let the variable dirCurrentID += 1
        $curId = $curId + 1;
        $queryEdit = "UPDATE `serverVar` SET `dirCurrentID`='$curId' WHERE 1";
        mysqli_query($db, $queryEdit);

        return $originArr;
    }
?>
