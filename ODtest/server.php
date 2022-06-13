<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once('user.php');
    require_once('crawler.php');

    // if the client request to see the directory list
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
        $user = new User($username);
        echo json_encode($user->listDirs());
	}

    // if the client click a directory to ask for seeing documents in it
    // also set the currentDir session here
    if(isset($_GET['listDocs'])) {
        $username = $_GET['username'];
        $user = new User($username);
        $dirName = $_GET['listDocs'];
        $listResult = $user->listDocs($dirName);
        echo json_encode($listResult);
        echo json_encode($dirName);
    }

    // if the client ask to create a directory
    if(isset($_POST['createDir'])) {
        $username = $_SESSION['username'];
        $user = new User($username);
        $dirName = $_POST['newDir'];
        if($dirName === '') {
            $_SESSION['msg'] = "Failed to add directory: name should not be empty";
        } else {
            $addResult = $user->addDir($dirName);

            if($addResult) {
                $_SESSION['msg'] = "Add directory successfully";
            } else {
                $_SESSION['msg'] = "Failed to add directory";
            }
        }
    }



    // if the client ask to remove the specified dir
    if(isset($_POST['removeDir'])) {
        $username = $_SESSION['username'];
        $user = new User($username);
        
        if(!isset($_SESSION['currentDir'])) {
            $_SESSION['msg'] = "You didn't click into any directory";
        } else {
            $dirName = $_SESSION['currentDir'];
            $removeResult = $user->removeDir($dirName);
            unset($_SESSION['currentDir']);
            unset($_SESSION['listResult']);
            if($removeResult) {
                $_SESSION['msg'] = "Remove directory ".$dirName." successfully";
            } else {
                $_SESSION['msg'] = "Fail to remove directory";
            }
        }
    }



    // if the client ask to add checked documents to another dir
    if(isset($_POST['addDocs'])) {
        $username = $_SESSION['username'];
        $user = new User($username);

        $docs = $_POST['addDocs'];
        $targetDir = $_POST['targetDir'];
        $failMsg = "Fail to add documents: ";
        $notSuccess = FALSE;
        foreach($docs as $docID) {
            if($user->addDocs($dirName, $docID) == FALSE) {
                $failMsg = $failMsg.$docID." ";
                $notSuccess = TRUE;
            }
        }
        if($notSuccess) {
            $failMsg = $failMsg."into your directory ".$dirName;
            $_SESSION['msg'] = $failMsg;
        }
    }



    // if the client click a directory to ask for seeing documents in it
    // also set the currentDir session here
    if(isset($_POST['listDocs'])) {
        $username = $_SESSION['username'];
        $user = new User($username);
        $dirName = $_POST['listDocs'];
        echo '<script type="text/javascript">',
        'console.log("in server list docs, user = '.$username.', and dir name = '.$dirName.'")',
        '</script>'
        ;
        $listResult = $user->listDocs($dirName);
        $_SESSION['currentDir'] = $dirName;
        $_SESSION['listResult'] = $listResult;
        /* to show the documents in the directory
        foreach($listResult as $oneRow) {
            $cur = $user->listMetadata($oneRow, "類目階層");
            var_dump($cur);
            echo '<script type="text/javascript">',
            'console.log("in server list docs, 題名 = '.$cur[1].'")',
            '</script>'
            ;
        }
        */
    }



    // if the client ask to delete a single document
    if(isset($_POST['removeDoc'])) {
        $username = $_SESSION['username'];
        $user = new User($username);
        $dirName = $_SESSION['currentDir'];
        $docID = $_POST['removeDoc'];
        $removeResult = $user->removeDoc($dirName, $docID);

        if($removeResult) {
            $_SESSION['msg'] = "Remove document successfully";
        } else {
            $_SESSION['msg'] = "Fail to remove document";
        }

    }

    // if the client ask to output a DocuXML
    if(isset($_POST['outputDocuXML'])) {
        // TODO: connect with MISSION 5
    }

    // if the client ask to list directories (ex. when loading user page)
    // this function will be called every time the user tries to do something and fresh the page
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo '<script type="text/javascript">',
        'console.log("in server: list dir list, user = '.$username.'")',
        '</script>'
        ;
        $user = new User($username);
        $_SESSION['dirList'] = $user->listDirs();
	}

    
    // upload CSV to a local directory
    if(isset($_POST['submitCSV'])) {
        $username = $_SESSION['username'];
        $user = new User($username);

        // [IMPORTANT]: remember to change the below directory!!!
        $targetDir = "/home/nomearod/ODuploadCSV/";
        $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
        $fileSource = $_POST['fileSource'];
        $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        // check if the file is acceptable
        // if not, don't do the following instructions
        if(checkUploadFile($fileType, $targetFile) == FALSE) return;
        // $targetFile = "/opt/lampp/htdocs/ODtest/NDAP.csv";
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

        
        
        // run another php script(assignCrawl) in background to assign IDs to different threads for crawling
        // return to user
        // 1.1 assignCrawl waits for every crawling thread to finish
        // 1.2 assignCrawl return a finish message to the user dir record in the database

        /*echo '<script type="text/javascript">',
        'console.log("in server: upload csv, ids = ");',
        'console.log("'.$ids.'");',
        '</script>'
        ;*/
    }

    function checkUploadFile($fileType, $targetFile) {
        // Check if file already exists
        // Need to change files' name in the future work
        $uploadOk = 1;
        /*if (file_exists($targetFile)) {
            $_SESSION['msg'] .= "File already exists.<br>";
            $uploadOk = 0;
        }*/
        // Allow certain file formats
        if($fileType != "csv") {
            $_SESSION['msg'] .=  "Only .csv files are allowed.<br>";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $_SESSION['msg'] .= "Your file was not uploaded.<br>";
        // if everything is ok, try to upload file
        } else {
            // echo "Trying to upload file...<br>";
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                $_SESSION['msg'] .= "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.<br>";
                return TRUE;
            } else {
                $_SESSION['msg'] .= "There was an error uploading your file.<br>";
            }
        }
        return FALSE;
    }

?>