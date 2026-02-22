<?php
session_start();
require_once("../config/database.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: manage_books.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM books WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

if(isset($_POST['update'])){

    $title = $_POST['title'];
    $author = $_POST['author'];

    $update = $conn->prepare("UPDATE books SET title=?, author=? WHERE id=?");
    $update->bind_param("ssi",$title,$author,$id);
    $update->execute();

    header("Location: manage_books.php");
    exit();
}
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

<h2>Edit Book</h2>

<div class="card">
<form method="POST">

<div class="form-group">
<label>Title</label>
<input type="text" name="title" value="<?php echo $book['title']; ?>" required>
</div>

<div class="form-group">
<label>Author</label>
<input type="text" name="author" value="<?php echo $book['author']; ?>" required>
</div>

<button name="update">Update Book</button>

</form>
</div>

</div>