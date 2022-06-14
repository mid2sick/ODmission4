<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once('user.php');

    // if the client request to see the directory list
    if (isset($_GET['docID']) && isset($_GET['docMetaID']) && isset($_GET['targetDir']) && isset($_GET['username'])) {
        $docID = $_GET['docID'];
        $docMetaID = $_GET['docMetaID'];
        $username = $_GET['username'];
        $targetDir = $_GET['targetDir'];
        echo json_encode("get targetDir: ".$targetDir.", docMetaID: ".$docMetaID.", docID: ".$docID);
        $user = new User($username);
        if($user->addDocs($targetDir, $docMetaID) && $user->removeDoc($docID)) {
            echo json_encode("成功移動資料");
        } else {
            echo json_encode("移動資料失敗");
        }
	}
?>