<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task-list";

// $servername = "sql112.infinityfree.com";
// $username = "if0_37072102";
// $password = "LJq4Gaxnjy";
// $dbname = "if0_37072102_task_list";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
    // echo "Connected successfully";
}


?>
