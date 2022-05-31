<?php
    require_once('user.php');

    // if the client ask to create a directory
    if(isset($_POST['createDir'])) {
        $userID = $_SESSION['userID'];
        $user = new User($userID);
        $dirName = $_POST['newDir'];
        if($dirName === '') {
            $_SESSION['msg'] = "Failed to add directory: name should not be empty";
        } else {
            $addResult = $user->addDir($dirName);
            $resultMsg = [];

            if($addResult) {
                $_SESSION['msg'] = "Add directory successfully";
            } else {
                $_SESSION['msg'] = "Failed to add directory";
            }
        }
    }



    // if the client ask to remove the specified dir
    if(isset($_POST['removeDir'])) {
        echo '<script type="text/javascript">',
        'console.log("in server removing...")',
        '</script>'
        ;
        $userID = $_SESSION['userID'];
        $user = new User($userID);
        
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



    // if the client ask to add documents
    if(isset($_POST['addDocs'])) {
        // TODO: connect with MISSION 2
        // 1. get the upload CSV

        // 2. send the CSV to MISSON 2
        // this is the API, what is inputWebAbbr? (python code)
        $ids = InputToIDs($inputWebAbbr, $inputFileName);

        // 3. open a waitThread to wait MISSON 2 （BLOCK）
        // 4. main thread return waiting message to client
        // 5. waitThread get IDs from MISSION 2
        // 6. waitThread assign each ID a thread to do metadata crawling
        // 7. waitThread waits for every crawling thread to finish
        // 8. waitThread return a finish message to the user page
        echo $resultMsg;
    }



    // if the client click a directory to ask for seeing documents in it
    // also set the currentDir session here
    if(isset($_POST['listDocs'])) {
        $userID = $_SESSION['userID'];
        $user = new User($userID);
        $dirName = $_POST['listDocs'];
        echo '<script type="text/javascript">',
        'console.log("in server list docs, user = '.$userID.', and dir name = '.$dirName.'")',
        '</script>'
        ;
        $listResult = $user->listDocs($dirName);
        $_SESSION['currentDir'] = $dirName;
        $_SESSION['listResult'] = $listResult;
        /*
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
        $userID = $_SESSION['userID'];
        $user = new User($userID);
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
    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];
        echo '<script type="text/javascript">',
        'console.log("in server: list dir list, user = '.$userID.'")',
        '</script>'
        ;
        $user = new User($userID);
        $_SESSION['dirList'] = $user->listDirs();
	}

    


?>