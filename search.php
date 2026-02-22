<?php
session_start();
require_once("../config/database.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user'){
    header("Location: ../auth/login.php");
    exit();
}

$result = null;

if(isset($_GET['search'])){
    $keyword = "%" . $_GET['keyword'] . "%";

    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
    $stmt->bind_param("ss",$keyword,$keyword);
    $stmt->execute();
    $result = $stmt->get_result();
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

<h2>Search Books</h2>

<div class="card">
<form method="GET">
<input type="text" name="keyword" placeholder="Enter title or author..." required>
<button name="search">Search</button>
</form>
</div>

<?php if($result){ ?>
<div class="card" style="margin-top:20px;">
<table>
<tr>
<th>Title</th>
<th>Author</th>
<th>Download</th>
</tr>

<?php while($book = $result->fetch_assoc()){ ?>
<tr>
<td><?php echo htmlspecialchars($book['title']); ?></td>
<td><?php echo htmlspecialchars($book['author']); ?></td>
<td>
<a href="../uploads/<?php echo $book['file_name']; ?>" download>Download</a>
</td>
</tr>
<?php } ?>

</table>
</div>
<?php } ?>

</div>
