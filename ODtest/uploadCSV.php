<?php
    header('Content-Type: multipart/form-data; charset=utf-8');
    require_once('user.php');
    require_once('crawler.php');

    
    // upload CSV to a local directory
    if(isset($_FILES['submitCSV']['name']) && isset($_POST['username']) && isset($_POST['dirName'])) {        
        $username = $_POST['username'];
        $user = new User($username);

        // [IMPORTANT]: remember to change the below directory!!!
        $targetDir = "/home/nomearod/ODuploadCSV/";
        $targetFile = $targetDir . basename($_FILES["submitCSV"]["name"]);
        $fileSource = getFileSource($_FILES["submitCSV"]["name"]);

        // $fileSource = $_POST['fileSource'];
        $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        // check if the file is acceptable
        // if not, don't do the following instructions
        if(uploadFile($fileType, $targetFile) == FALSE) return;
        $ids = getIDs($targetFile, $fileSource);
        echo "get ids:\n";
        echo $ids;
        /*
        $ids = json_decode($ids);
        var_dump($ids);
        $crawlFail = [];
        // call the crawler
        foreach($ids as $docID) {
            if(crawlMetadata($docID, $fileSource) == FALSE) {
                $crawlFail[] = $docID;
            }
        }

        if($crawlFail[] != NULL) {
            echo "Fail to crawl metadata:\n";
            foreach($crawlFail as $failID) {
                echo $failID." ";
            }
        }
        
        // edit the Dir_Doc table
        $dirName = $_POST['dirName'];
        $addFail = [];
        foreach($ids as $docID) {
            echo $docID;
            if($user->addDocs($dirName, $docID) == FALSE) {
                $addFail[] = $docID;
            }
        }
        
        if($addFail[] != NULL) {
            echo "Fail to add documents:\n";
            foreach($addFail as $failID) {
                echo $failID." ";
            }
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
            echo "Only .csv files are allowed.\n";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Your file was not uploaded.\n";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["submitCSV"]["tmp_name"], $targetFile)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["submitCSV"]["name"])). " has been uploaded.\n";
                return TRUE;
            } else {
                echo "There was an error uploading your file.\n";
            }
        }
        return FALSE;
    }

    function getFileSource($filename) {
        if(substr($filename, 0, 5) === "AHCMS") return "AHCMS";
        if(substr($filename, 0, 5) === "AHTWH") return "AHTWH";
        if(substr($filename, 0, 5) === "NDAP") return "NDAP";
        if(substr($filename, 0, 5) === "tlcda") return "TLCDA";
    }

?>