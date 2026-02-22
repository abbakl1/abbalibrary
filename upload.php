<?php
session_start();
include("../config/database.php");

// ==========================
// PROTECT PAGE (ADMIN ONLY)
// ==========================
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

// ==========================
// HANDLE UPLOAD
// ==========================
if(isset($_POST['upload'])){

    $title = trim($_POST['title']);

    if(empty($title)){
        $message = "Book title is required!";
    } else {

        if(isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0){

            $fileName = $_FILES['pdf']['name'];
            $fileTmp  = $_FILES['pdf']['tmp_name'];
            $fileSize = $_FILES['pdf']['size'];

            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Allow only PDF
            if($fileExt != "pdf"){
                $message = "Only PDF files are allowed!";
            }
            else{

                // Rename file to avoid duplicates
                $newFileName = time() . "_" . $fileName;
                $targetPath = "../uploads/" . $newFileName;

                if(move_uploaded_file($fileTmp, $targetPath)){

                    // Insert into database
                    $stmt = $conn->prepare("INSERT INTO books (title, file_name, uploaded_by, uploaded_at) VALUES (?, ?, ?, NOW())");
                    $stmt->bind_param("ssi", $title, $newFileName, $_SESSION['user_id']);
                    $stmt->execute();

                    $message = "Book uploaded successfully!";
                }
                else{
                    $message = "Failed to upload file!";
                }
            }

        } else {
            $message = "Please select a PDF file!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Book</title>
    <link rel="stylesheet" href="../public/assets/style.css">
</head>
<body>

<div class="container card">
    <h2>Upload New Book</h2>

    <?php if($message != ""): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Enter Book Title" required>
        <input type="file" name="pdf" accept=".pdf" required>
        <button type="submit" name="upload">Upload Book</button>
    </form>

    <br>
    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
