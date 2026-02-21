<?php
// ===============================
// 1️⃣ Start Session (Required for Role)
// ===============================
session_start();

// ===============================
// 2️⃣ Database Connection
// ===============================
include("config/db.php");

// ===============================
// 3️⃣ Live Search Logic
// ===============================
if(isset($_POST['search'])) {

    $search = "%".$_POST['search']."%";

    // Secure Prepared Statement
    $stmt = mysqli_prepare($conn, 
        "SELECT * FROM students WHERE name LIKE ? ORDER BY id DESC");

    mysqli_stmt_bind_param($stmt, "s", $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // ===============================
    // 4️⃣ Output Table Rows
    // ===============================
    while($row = mysqli_fetch_assoc($result)) {

        echo "<tr>";

        echo "<td>".$row['id']."</td>";
        echo "<td>".$row['name']."</td>";
        echo "<td>".$row['email']."</td>";
        echo "<td>".$row['phone']."</td>";
        echo "<td>".$row['course']."</td>";

        echo "<td>";

        // Edit Button (Everyone can edit)
        echo "<a href='update_student.php?id=".$row['id']."' 
                class='btn btn-warning btn-sm me-1'>
                <i class='bi bi-pencil'></i>
              </a>";

        // Delete Button (Admin Only)
        if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") {

            echo "<a href='delete_student.php?id=".$row['id']."' 
                    class='btn btn-danger btn-sm'
                    onclick=\"return confirm('Delete this student?')\">
                    <i class='bi bi-trash'></i>
                  </a>";
        }

        echo "</td>";
        echo "</tr>";
    }
}
?>