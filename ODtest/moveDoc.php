<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once('user.php');

    // if the client request to see the directory list
    if (isset($_GET['docID']) && isset($_GET['targetDir']) && isset($_GET['username'])) {
        $docID = $_GET['docID'];
        $username = $_GET['username'];
        $targetDir = $_GET['targetDir'];
        // echo json_encode("get targetDir: ".$targetDir.", docMetaID: ".$docMetaID.", docID: ".$docID);
        $user = new User($username);
        echo json_encode("moveDoc.php 0628");
        echo json_encode($user->moveDoc($docID, $targetDir));
	}
?>