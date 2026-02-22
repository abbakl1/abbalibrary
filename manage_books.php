<?php
session_start();

// Only admin can access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

require_once("../config/database.php");

$message = "";

if(isset($_POST['upload'])){
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $file = $_FILES['pdf_file'];

    // Validate file
    $fileName = time() . "_" . basename($file['name']);
    $target = "../uploads/" . $fileName;
    $fileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));

    if($fileType != "pdf"){
        $message = "Only PDF files are allowed!";
    } else {
        if(move_uploaded_file($file['tmp_name'], $target)){
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO books (title, author, file_path, uploaded_by, uploaded_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssi", $title, $author, $target, $_SESSION['user_id']);
            if($stmt->execute()){
                $message = "Book uploaded successfully!";
            } else {
                $message = "Database error!";
            }
        } else {
            $message = "Failed to move uploaded file!";
        }
    }
}
?>

<link rel="stylesheet" href="../public/assets/style.css">

<div class="container card">
<h2>Upload Book</h2>
<?php if($message) echo "<p>$message</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Book Title" required>
    <input type="text" name="author" placeholder="Author Name" required>
    <input type="file" name="pdf_file" accept="application/pdf" required>
    <button name="upload">Upload Book</button>
</form>

<p><a href="dashboard.php">Back to Admin Dashboard</a></p>
</div>