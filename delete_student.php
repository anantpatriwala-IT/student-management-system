<?php
session_start();

// 🔐 Allow only admin to delete
if($_SESSION['role'] != "admin") {
    if($_SESSION['role'] != "admin") {
    header("Location: dashboard.php?error=access");
    exit();
}
}

include("config/db.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: dashboard.php?success=deleted");
    exit();
}
?>