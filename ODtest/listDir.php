<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once('user.php');
    require_once('crawler.php');

    // if the client request to see the directory list
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
        $user = new User($username);
        echo json_encode($user->listDirs());
	}
?>