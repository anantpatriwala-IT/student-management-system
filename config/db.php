<?php
$servername = "sql211.infinityfree.com";
$username   = "if0_41212567";
$password   = "YOUR_DATABASE_PASSWORD";
$database   = "if0_41212567_student_db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>
