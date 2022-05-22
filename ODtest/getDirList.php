<?php
    // DIR will be the return json object
    class DIR {
        // Properties
        public $IDs;
        public $names;
    }
    $dir = new DIR();
    
    $result_array = array();
    $username = $_POST['username'];

    
    $db = mysqli_connect('localhost', 'root', '', 'loginPage') or die("Connect failed: %s\n". $db -> error);
    $query = "SELECT `dirName`,`dirID` FROM `directory` WHERE `username`='$username'";
    
    $result = mysqli_query($db, $query);
    $dir->IDs = "[";
    $dir->names = "[";
    
    // echo $query;
    
    while($row = mysqli_fetch_assoc($result)) {
        $dir->IDs = $dir->IDs.$row["dirID"].',';
        $dir->names = $dir->names.'"'.$row["dirName"].'",';
    }
    
    $dir->IDs = rtrim($dir->IDs, ",");
    $dir->IDs .= "]";
    $dir->names = rtrim($dir->names, ",");
    $dir->names .= "]";
    
    // send a JSON encoded array to client
    header('Content-type: application/json');
    echo json_encode($dir);
    $db->close();
?>