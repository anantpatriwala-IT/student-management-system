<?php
// ===============================
// 1️⃣ Start Session & Security Headers
// ===============================
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("config/db.php");

// ===============================
// 2️⃣ Protect Page (Login Required)
// ===============================
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// ===============================
// 3️⃣ Pagination Setup
// ===============================
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// ===============================
// 4️⃣ Secure Search + Fetch Data
// ===============================
if(isset($_GET['search']) && !empty($_GET['search'])) {

    $search = "%".$_GET['search']."%";

    $stmt = mysqli_prepare($conn,
        "SELECT * FROM students WHERE name LIKE ? ORDER BY id DESC LIMIT ?, ?");
    mysqli_stmt_bind_param($stmt, "sii", $search, $start, $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else {

    $result = mysqli_query($conn,
        "SELECT * FROM students ORDER BY id DESC LIMIT $start, $limit");
}

// ===============================
// 5️⃣ Total Students Counter
// ===============================
$totalStudents = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM students")
)['total'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ===============================
     🔝 Navbar
================================ -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">Student Management</span>
    <span class="text-white">Welcome, <?= $_SESSION['admin']; ?></span>
    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout
</button>
  </div>
</nav>

<div class="container mt-4">

<!-- ===============================
     🔔 Success Alert
================================ -->
<?php if(isset($_GET['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php
        if($_GET['success'] == "added") echo "Student Added Successfully!";
        if($_GET['success'] == "updated") echo "Student Updated Successfully!";
        if($_GET['success'] == "deleted") echo "Student Deleted Successfully!";
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- ===============================
     📊 Dashboard Card
================================ -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="card shadow">
      <div class="card-body">
        <h5>Total Students</h5>
        <h3><?= $totalStudents; ?></h3>
        <i class="bi bi-people-fill fs-1 text-primary"></i>
      </div>
    </div>
  </div>
</div>

<!-- ===============================
     ➕ Add Student Button
================================ -->
<a href="add_student.php" class="btn btn-primary mb-3">
<i class="bi bi-plus-circle"></i> Add Student
</a>

<!-- ===============================
     🔍 Live Search Input
================================ -->
<input type="text" id="search" class="form-control mb-3" placeholder="Live Search Student...">

<!-- ===============================
     📋 Students Table
================================ -->
<table class="table table-hover shadow">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Course</th>
<th>Actions</th>
</tr>
</thead>

<tbody id="studentTable">

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= $row['id']; ?></td>
<td><?= $row['name']; ?></td>
<td><?= $row['email']; ?></td>
<td><?= $row['phone']; ?></td>
<td><?= $row['course']; ?></td>
<td>
<a href="update_student.php?id=<?= $row['id']; ?>" 
class="btn btn-warning btn-sm">
<i class="bi bi-pencil"></i>
</a>

<a href="delete_student.php?id=<?= $row['id']; ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this student?')">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>

<!-- ===============================
     📄 Pagination Buttons
================================ -->
<?php
$total = mysqli_query($conn, "SELECT COUNT(id) FROM students");
$total_rows = mysqli_fetch_array($total)[0];
$total_pages = ceil($total_rows / $limit);

for($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=".$i."' class='btn btn-secondary m-1'>".$i."</a>";
}
?>

</div>

<!-- ===============================
     📦 Bootstrap JS
================================ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- ===============================
     ⚡ Live Search AJAX
================================ -->
<script>
document.getElementById("search").addEventListener("keyup", function() {

    let search = this.value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_students.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        document.getElementById("studentTable").innerHTML = this.responseText;
    }

    xhr.send("search=" + search);
});
</script>
<div class="modal fade" id="logoutModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        Are you sure you want to logout?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="logout.php" class="btn btn-danger">Yes, Logout</a>
      </div>

    </div>
  </div>
</div>
</body>
</html>