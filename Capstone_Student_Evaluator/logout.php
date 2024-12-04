<?php
session_start(); //starts session

//unsets all session variables
$_SESSION = [];

//destroys session on logout
session_destroy();

//redirects to login page
header("Location: login.php");
exit;
?>

<!DOCTYPE html>
<html>
<head>
	<!--
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 10/15/2024  

    Filename: logout.php
   -->
	<title>Logout</title>
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
</body>
</html>
