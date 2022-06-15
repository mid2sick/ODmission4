<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once('user.php');
    require_once('registration/Lib_security.php');

    my_session_start();

    // return the username if the user has login
    if(isset($_SESSION['username'])) {
        echo json_encode("in getting user name");
        echo json_encode($_SESSION['username']);
    } else {
        echo json_encode("workshop04");
    }
?>