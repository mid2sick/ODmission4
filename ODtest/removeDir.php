<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once('user.php');

    // if the client ask to create a directory
    if(isset($_GET['removeDir']) && isset($_GET['username'])) {
        $dirName = $_GET['removeDir'];
        $username = $_GET['username'];
        $user = new User($username);
       
        if( $user->removeDir($dirName)) {
            echo json_encode("Remove directory successfully");
        } else {
            echo json_encode("Failed to remove directory");
        }
    }

?>