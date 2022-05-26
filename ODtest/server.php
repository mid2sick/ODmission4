<?php
    // if the client ask to create a directory
    if(isset($_POST['createDir'])) {
        $user = $_SESSION['user'];
        $dirName = $_POST['createDir'];
        $addResult = $user->addDir($dirName);
        $resultMsg = [];

        if($addResult) {
            $resultMsg[] = "Add directory successfully";
        } else {
            $resultMsg[] = "Fail to add directory";
        }

        $resultMsg[] = $user->listDirs();
        echo $resultMsg;
    }

    // if the client ask to list directories (ex. when loading user page)
    // how to do this automatically? call js immediately when load in page?
    if(isset($_POST['listDir'])) {
        $user = $_SESSION['user'];
        $listResult = $user->listDir();

        $resultMsg[] = $listResult;
        echo $resultMsg;
    }

    // if the client ask to remove the specified dir
    if(isset($_POST['removeDir'])) {
        $user = $_SESSION['user'];
        $dirName = $_POST['removeDir'];
        $removeResult = $user->removeDir($dirName);

        if($removeResult) {
            $resultMsg[] = "Remove directory successfully";
        } else {
            $resultMsg[] = "Fail to remove directory";
        }

        // return the update directory list
        $resultMsg[] = $user->listDirs();
        echo $resultMsg;
    }

    // if the client ask to add documents
    if(isset($_POST['addDocs'])) {
        // TODO: connect with MISSION 2
        // 1. get the upload CSV
        // 2. send the CSV to MISSON 2
        // 3. open a waitThread to wait MISSON 2 （BLOCK）
        // 4. main thread return waiting message to client
        // 5. waitThread get IDs from MISSION 2
        // 6. waitThread assign each ID a thread to do metadata crawling
        // 7. waitThread waits for every crawling thread to finish
        // 8. waitThread return a finish message to the user page
        echo $resultMsg;
    }

    // if the client click a directory to askt to see documents in it
    if(isset($_POST['listDocs'])) {
        $user = $_SESSION['user'];
        $dirName = $_POST['listDocs'];
        $listResult = $user->listDocs();

        $resultMsg[] = $user->listDirs();
        echo $resultMsg;
    }

    // if the client ask to delete a single document
    if(isset($_POST['removeDoc'])) {
        $user = $_SESSION['user'];
        $dirName = $_POST['dirName'];
        $docID = $_POST['removeDoc'];
        $removeResult = $user->removeDoc($dirName, $docID);

        if($removeResult) {
            $resultMsg[] = "Remove document successfully";
        } else {
            $resultMsg[] = "Fail to remove document";
        }

        // return the update documents list
        $resultMsg[] = $user->listDocs();
        echo $resultMsg;
    }

    // if the client ask to output a DocuXML
    if(isset($_POST['outputDocuXML'])) {
        // TODO: connect with MISSION 5
    }

?>