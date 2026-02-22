<?php
session_start();
require_once("../config/database.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    if($id != $_SESSION['user_id']){ // prevent self delete
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
    }

    header("Location: manage_users.php");
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<link rel="stylesheet" href="../public/assets/style.css">

<div class="sidebar">
<h3>Admin Panel</h3>
<a href="dashboard.php">Dashboard</a>
<a href="manage_books.php">Manage Books</a>
<a href="manage_users.php">Manage Users</a>
<a href="../auth/logout.php">Logout</a>
</div>

<div class="content">

<h2>Manage Users</h2>

<div class="card">
<table>
<tr>
<th>ID</th>
<th>Full Name</th>
<th>Email</th>
<th>Role</th>
<th>Action</th>
</tr>

<?php while($user = $users->fetch_assoc()){ ?>
<tr>
<td><?php echo $user['id']; ?></td>
<td><?php echo $user['fullname']; ?></td>
<td><?php echo $user['email']; ?></td>
<td><?php echo $user['role']; ?></td>
<td>
<?php if($user['id'] != $_SESSION['user_id']){ ?>
<a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Delete this user?')">Delete</a>
<?php } ?>
</td>
</tr>
<?php } ?>

</table>
</div>

</div>