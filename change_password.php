<?php
session_start();
require_once("../config/database.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user'){
    header("Location: ../auth/login.php");
    exit();
}

$message = "";
$id = $_SESSION['user_id'];

if(isset($_POST['change'])){

    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if(password_verify($current,$user['password'])){

        $newHash = password_hash($new,PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $update->bind_param("si",$newHash,$id);
        $update->execute();

        $message = "<div class='success'>Password updated successfully!</div>";

    } else {
        $message = "<div class='error'>Current password incorrect!</div>";
    }
}
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

<h2>Change Password</h2>

<?php echo $message; ?>

<div class="card">
<form method="POST">

<div class="form-group">
<label>Current Password</label>
<input type="password" name="current_password" required>
</div>

<div class="form-group">
<label>New Password</label>
<input type="password" name="new_password" required>
</div>

<button name="change">Update Password</button>

</form>
</div>

</div>