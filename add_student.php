<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if(isset($_POST['submit'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];

    $stmt = mysqli_prepare($conn,
        "INSERT INTO students (name,email,phone,course) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "ssss", $name,$email,$phone,$course);
    mysqli_stmt_execute($stmt);

    header("Location: dashboard.php?success=added");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Student</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5">

<div class="card shadow-lg p-4">

<h3 class="mb-4 text-primary">Add New Student</h3>

<form method="POST">
<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Phone</label>
<input type="text" name="phone" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Course</label>
<input type="text" name="course" class="form-control" required>
</div>

</div>

<button type="submit" name="submit" class="btn btn-success">
Add Student
</button>

<a href="dashboard.php" class="btn btn-secondary ms-2">
Back
</a>

</form>

</div>
</div>

</body>
</html>