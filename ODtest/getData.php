<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once('user.php');
    
    // if the client click a directory to ask for seeing documents in it
    // also set the currentDir session here
    if(isset($_GET['listDocs']) && isset($_GET['username'])) {
        $username = $_GET['username'];
        $user = new User($username);
        $dirName = $_GET['listDocs'];
        $listResult = $user->listDocs($dirName);
        $res = array("list" => $listResult, "dirName" => $dirName);
        echo json_encode($res);
    }
?>