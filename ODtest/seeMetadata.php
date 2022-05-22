<?php 
    $result_array = array();
    
    /* Create connection to database loginPage*/
    $loginDB = mysqli_connect('localhost', 'root', '', 'loginPage') or die("Connect failed: %s\n". $loginDB -> error);
    /* SQL query to get results from database */
    $username = $_POST['username'];
    $dirID = $_POST['id'];
    
    // check if the user is the requested directory's owner
    // Yes, then request the directory's data
    // No, then return error

    $query = "SELECT `username` FROM `directory` WHERE dirID='$dirID'";
    $result = mysqli_query($loginDB, $query);
    $realUser = mysqli_fetch_assoc($result);
    if($realUser['username'] === $username) {
        array_push($result_array, "correct user");
        
        $query = "SELECT `metadata` FROM `directory` WHERE dirID='$dirID'";
        $result = mysqli_query($loginDB, $query);
        $document = mysqli_fetch_assoc($result);
        array_push($result_array, $document);
    } else {
        array_push($result_array, "wrong user");
    }
    
    // send a JSON encoded array to client
    header('Content-type: application/json');
    echo json_encode($result_array);

    $loginDB->close();
?>