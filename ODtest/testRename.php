<?php
    require_once('user.php');
    renameDir("workshop04", "my first dir", "first dir");

    function renameDir($username, $dirName, $newName) {
        $user = new User($username);
        echo $user->renameDir($dirName, $newName);
    }
?>