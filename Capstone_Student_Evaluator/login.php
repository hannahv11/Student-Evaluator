<?php
session_start();
include 'db_connection.php';
include 'active_user.php';


//checks if info is posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //fetches user info from database when login is successful
	//had to edit to work with PDO -HV
    $stmt = $pdo->prepare("SELECT id, role, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

	//fetches proper role and starts session if login was successful, throws message if not.
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel = "stylesheet" href="navigation.css">
	<link rel = "stylesheet" href="signup.css">
</head>

<body>
<header>
<div class="topnav">
  <a href="index.php">Peer Review Form</a>
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
			<input id="username" type="text" name="username"><br><br>
			
		<label for="password">Password:</label>
			<input id="password" type="password" name="password"><br><br>

			<input id="button" type="submit" value="Login"><br><br>

			<a href="signup.php">Click to Signup</a><br><br>
		</form>
	</div>

</body>
</html>
