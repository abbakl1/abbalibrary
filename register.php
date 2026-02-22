<?php
session_start();
require_once("../config/database.php");

// Prevent logged-in users from accessing register
if(isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$message = "";

if(isset($_POST['register'])){

    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($fullname) || empty($email) || empty($password)){
        $message = "<div class='error'>All fields are required!</div>";
    } 
    elseif(strlen($password) < 6){
        $message = "<div class='error'>Password must be at least 6 characters!</div>";
    }
    else {

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){

            $message = "<div class='error'>Email already registered!</div>";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (fullname,email,password,role,created_at) VALUES (?,?,?,'user',NOW())");
            $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

            if($stmt->execute()){
                $message = "<div class='success'>Registration successful! <a href='login.php'>Login here</a></div>";
            } else {
                $message = "<div class='error'>Something went wrong!</div>";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../public/assets/style.css">
</head>
<body>

<div class="container">
<div class="card">

<h2>Create Account</h2>

<?php echo $message; ?>

<form method="POST" autocomplete="off">

<div class="form-group">
<label>Full Name</label>
<input type="text" name="fullname" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" required>
</div>

<button type="submit" name="register">Register</button>

</form>

<p style="margin-top:15px;">
Already have an account? <a href="login.php">Login</a>
</p>

</div>
</div>

</body>
</html>