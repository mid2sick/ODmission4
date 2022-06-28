<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once('user.php');

    // if the client request to see the directory list
    if (isset($_GET['docID']) && isset($_GET['targetDir']) && isset($_GET['username'])) {
        $docID = $_GET['docID'];
        $username = $_GET['username'];
        $targetDir = $_GET['targetDir'];
        $user = new User($username);
        echo json_encode($user->copyDoc($docID, $targetDir));
        // echo json_encode($user->addDocs($targetDir, $docID));
	}
?>