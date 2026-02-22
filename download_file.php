<?php
session_start();
include("config/database.php");

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: auth/login.php");
    exit;
}

// Check if book ID is provided
if(!isset($_GET['id'])){
    die("No book selected for download.");
}

$bookId = intval($_GET['id']);

// Get book info
$stmt = $conn->prepare("SELECT id, title, file_name FROM books WHERE id=?");
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if(!$book){
    die("Book not found.");
}

// File path
$filePath = __DIR__ . "/uploads/" . $book['file_name'];

if(file_exists($filePath)){
    // Increment download count
    $conn->query("UPDATE books SET downloads = downloads + 1 WHERE id=".$book['id']);

    // Force download
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
} else {
    die("File does not exist.");
}
$conn->query("UPDATE books SET downloads = downloads + 1 WHERE id=".$book['id']);
?>
