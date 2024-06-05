<?php
session_start();

// Include connection to the database
@include "conn.php";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // If user is already logged in, redirect them to admin panel
    header("location: admin.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    // Perform your login validation here
    
    // Assuming login validation is successful
    // Start the session and redirect to admin panel
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $_POST['username'];
    header('Location: admin.php'); // Redirect to admin.php after successful login
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/loginstyle.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

    </style>
</head>
<body>
    <img class="wave" src="img/bg_1.jpg">
        </div>
        <div class="login-content">
            <!-- Login Form -->
            <form name="form" action="login.php" method="POST">
                <img src="img/admin icon.png" class="logo">
                <h2 class="title">Welcome Admin</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Username</h5>
                        <input required type="text" name="username" class="input" value="<?php echo isset($username) ? $username : ''; ?>">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i"> 
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" name="password" class="input" required>
                    </div>
                </div>
                <input type="submit" class="btn" name="login" value="Login" required>
            </form>
            <?php if (!empty($error)) { echo $error; } ?>
        </div>
    </div>
    <script type="text/javascript" src="js/loginmain.js"></script>
</body>
</html>
