<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

//shows newly registered user success message if signed up correctly. Retrieves global variable from signup.php
if (isset($_SESSION['signup_success'])) {
    echo '<div class="success-message">' . $_SESSION['signup_success'] . '</div>';
    unset($_SESSION['signup_success']);
}

//checks if form is properly submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //retrieves user info upon login
    $username = $_POST['username'];
    $password = $_POST['password'];

    //fetches user info from database when login is successful
    $stmt = $pdo->prepare("SELECT id, role, password FROM users 
                            WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

	//fetches proper role and starts session if login was successful, throws message if not.
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] === 'student') {
            header("Location: student.php");
        } 
        else 
        {
            header("Location: faculty.php");
        }
        exit;

    } 
    else 
    {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!--
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 10/15/2024  

    Filename: login.php
   -->
	<title>Login</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel = "stylesheet" href="navigation.css">
	<link rel = "stylesheet" href="signup.css">
    <link rel = "stylesheet" href="mobile.css">
</head>

<body>
    <header>
        <div class="topnav">
            <a href="signup.php">Register</a>
            <a href="faculty.php">Faculty</a>
            <a href="student.php">Student</a>
            <a href="login.php">Login</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

<body>
	<div id="login">
		<form method="post">
			<h1>Login</h1>
                <label for="username">Username:</label>
                    <input id="username" type="text" name="username">
                    <br><br>
                <label for="password">Password:</label>
                    <input id="password" type="password" name="password">
                    <br><br>
                    <input id="button" type="submit" value="Login">
                    <br><br>
			<a href="signup.php">Click to Signup</a><br><br>
		</form>
	</div>
</body>
</html>
