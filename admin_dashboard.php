<?php
include("../config/database.php");
include("../models/Book.php");

// Protect admin page
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$bookModel = new Book($conn);
$limit = 5; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : "";

$totalBooks = $bookModel->countBooks($search);
$totalPages = ceil($totalBooks / $limit);
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$totalBooks = $conn->query("SELECT COUNT(*) as total FROM books")->fetch_assoc()['total'];

$books = $bookModel->getBooks($search, $limit, $offset);
?>

<!DOCTYPE html>
<html>
<head>
    <title>ABK Library - Admin Dashboard</title>
    <link rel="stylesheet" href="../public/assets/style.css">
    <script src="../public/assets/js/main.js"></script>
</head>
<body>
<div class="container card">
    <h2>Admin Dashboard</h2>
    <p><a href="profile.php">Profile</a> | <a href="../logout.php">Logout</a></p>

    <h3>Add New Book</h3>
    <form method="POST" action="admin_books.php" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="file" name="cover" accept="image/*" required>
        <input type="file" name="file" accept="application/pdf" required>
        <button type="submit" name="add_book">Add Book</button>
    </form>

    <h3>Books List</h3>
    <input type="text" id="search" placeholder="Search books by title or author" value="<?php echo htmlspecialchars($search); ?>">
    
    <div id="book-list">
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Downloads</th>
                <th>Action</th>
            </tr>
            <?php while($book = $books->fetch_assoc()){ ?>
            <tr>
                <td><?php echo htmlspecialchars($book['title']); ?></td>
                <td><?php echo htmlspecialchars($book['author']); ?></td>
                <td><?php echo $book['downloads']; ?></td>
                <td>
                    <a href="admin_books.php?delete_id=<?php echo $book['id']; ?>" onclick="return confirm('Delete this book?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>

        <!-- Pagination -->
        <div style="margin-top:10px;">
            <?php if($page > 1){ ?>
                <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
            <?php } ?>
            Page <?php echo $page; ?> of <?php echo $totalPages; ?>
            <?php if($page < $totalPages){ ?>
                <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>

