<?php 
    $result_array = array();

    /* Create connection */
    $db = mysqli_connect('localhost', 'root', '', 'loginPage') or die("Connect failed: %s\n". $db -> error);
    /* SQL query to get results from database */
    $username = $_POST['username'];
    $query = "SELECT `directoryIDs`, `directoryNames` FROM `login` WHERE username='$username'";
    $result = mysqli_query($db, $query);
    
    // If there are results from database push to result array 
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($result_array, $row);
        }
    }
    /* send a JSON encded array to client */
    header('Content-type: application/json');
    echo json_encode($result_array);

    $db->close();
?>