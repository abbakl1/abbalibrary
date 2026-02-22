<?php
session_start();
require_once("../config/database.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user'){
    header("Location: ../auth/login.php");
    exit();
}

$id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<link rel="stylesheet" href="../public/assets/style.css">

<div class="sidebar">
<h3>User Panel</h3>
<a href="dashboard.php">Dashboard</a>
<a href="search.php">Search Books</a>
<a href="profile.php">Profile</a>
<a href="change_password.php">Change Password</a>
<a href="../auth/logout.php">Logout</a>
</div>

<div class="content">

<h2>My Profile</h2>

<div class="card">
<p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
<p><strong>Role:</strong> <?php echo $user['role']; ?></p>
<p><strong>Member Since:</strong> <?php echo $user['created_at']; ?></p>
</div>

</div>