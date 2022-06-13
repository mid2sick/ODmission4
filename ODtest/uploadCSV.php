<?php
    header('Content-Type: multipart/form-data; charset=utf-8');
    require_once('user.php');
    require_once('crawler.php');

    
    // upload CSV to a local directory
    if(isset($_FILES['submitCSV']['name']) && isset($_POST['username'])) {        
        $username = $_POST['username'];
        $user = new User($username);

        // [IMPORTANT]: remember to change the below directory!!!
        $targetDir = "/home/nomearod/ODuploadCSV/";
        $targetFile = $targetDir . basename($_FILES["submitCSV"]["name"]);

        // $fileSource = $_POST['fileSource'];
        $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        // check if the file is acceptable
        // if not, don't do the following instructions
        if(uploadFile($fileType, $targetFile) == FALSE) return;
        $ids = getIDs($targetFile, "NDAP");
        echo "get ids:\n";
        echo $ids;
        // $targetFile = "/opt/lampp/htdocs/ODtest/NDAP.csv";
        /*
        $ids = getIDs($targetFile, $fileSource);
        $ids = json_decode($ids);
        var_dump($ids);
        
        // edit the Dir_Doc table
        $dirName = $_SESSION['currentDir'];
        $failMsg = "Fail to add documents: ";
        $notSuccess = FALSE;
        foreach($ids as $docID) {
            echo $docID;
            if($user->addDocs($dirName, $docID) == FALSE) {
                $failMsg = $failMsg.$docID." ";
                $notSuccess = TRUE;
            }
        }
        
        if($notSuccess) {
            $failMsg = $failMsg."into your directory ".$dirName;
            $_SESSION['msg'] = $failMsg;
        }
        */
    }
    
    function uploadFile($fileType, $targetFile) {
        // Check if file already exists
        // Need to change files' name in the future work
        $uploadOk = 1;
        /*if (file_exists($targetFile)) {
            $_SESSION['msg'] .= "File already exists.<br>";
            $uploadOk = 0;
        }*/
        // Allow certain file formats
        if($fileType != "csv") {
            echo "Only .csv files are allowed.<br>";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Your file was not uploaded.<br>";
        // if everything is ok, try to upload file
        } else {
            // echo "Trying to upload file...<br>";
            if (move_uploaded_file($_FILES["submitCSV"]["tmp_name"], $targetFile)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["submitCSV"]["name"])). " has been uploaded.<br>";
                return TRUE;
            } else {
                echo "There was an error uploading your file.<br>";
            }
        }
        return FALSE;
    }

?>