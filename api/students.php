<?php
include("../config/db.php");

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if($method == "GET") {
    $result = mysqli_query($conn, "SELECT * FROM students");
    $students = [];

    while($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }

    echo json_encode($students);
}
?>
