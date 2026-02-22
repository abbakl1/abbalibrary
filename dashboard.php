<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

require_once("../config/database.php");

// Fetch books
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
if($search){
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY uploaded_at DESC");
    $like = "%$search%";
    $stmt->bind_param("ss",$like,$like);
    $stmt->execute();
    $result = $stmt->get_result();
}else{
    $result = $conn->query("SELECT * FROM books ORDER BY uploaded_at DESC");
}
?>

<link rel="stylesheet" href="../public/assets/style.css">

<div class="container">
    <aside class="sidebar">
        <h2>ABK Library</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="dashboard.php">Search Books</a></li>
            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
        <form method="GET" action="dashboard.php" class="search-form">
            <input type="text" name="search" placeholder="Search by title or author" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </aside>

    <main class="main-content">
        <h2>Welcome, <?php echo $_SESSION['fullname']; ?>!</h2>

        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Downloads</th>
                <th>Action</th>
            </tr>
            <?php while($book = $result->fetch_assoc()){ ?>
            <tr>
                <td><?php echo htmlspecialchars($book['title']); ?></td>
                <td><?php echo htmlspecialchars($book['author']); ?></td>
                <td><?php echo $book['downloads']; ?></td>
                <td><a href="../download_file.php?id=<?php echo $book['id']; ?>">Download</a></td>
            </tr>
            <?php } ?>
        </table>
    </main>
</div>