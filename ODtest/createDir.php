<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once('user.php');
    require_once('crawler.php');

    // if the client ask to create a directory
    if(isset($_GET['createDir']) && isset($_GET['username'])) {
        $dirName = $_GET['createDir'];
        $username = $_GET['username'];
        $user = new User($username);
        if($dirName === '') {
            echo json_encode("Failed to add directory: name should not be empty");
        } else {
            $addResult = $user->addDir($dirName);
            if($addResult) {
                echo json_encode("Add directory successfully");
            } else {
                echo json_encode("Failed to add directory");
            }
        }
    }

?>