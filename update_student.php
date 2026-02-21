<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];

    $stmt = mysqli_prepare($conn,
        "UPDATE students SET name=?, email=?, phone=?, course=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssssi", $name,$email,$phone,$course,$id);
    mysqli_stmt_execute($stmt);

    header("Location: dashboard.php?success=updated");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Update Student</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow-lg p-4">

<h3 class="mb-4 text-warning">Update Student</h3>

<form method="POST">
<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Name</label>
<input type="text" name="name" class="form-control"
value="<?= $row['name'] ?>" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control"
value="<?= $row['email'] ?>" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Phone</label>
<input type="text" name="phone" class="form-control"
value="<?= $row['phone'] ?>" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Course</label>
<input type="text" name="course" class="form-control"
value="<?= $row['course'] ?>" required>
</div>

</div>

<button type="submit" name="update" class="btn btn-warning">
Update Student
</button>

<a href="dashboard.php" class="btn btn-secondary ms-2">
Back
</a>

</form>

</div>
</div>

</body>
</html>