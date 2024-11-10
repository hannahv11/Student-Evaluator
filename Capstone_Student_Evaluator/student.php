<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

// Debug line to verify session contents (remove or comment out in production)
echo "Session ID: " . (isset($_SESSION['id']) ? $_SESSION['id'] : "Not set") . " | Role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : "Not set") . "<br>";

//checks if current login is student role
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
   //sends you back to login if not, or logged in as faculty
   header("Location: login.php");
   exit;
}

//Need code for students to view their already submitted reviews?

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!--
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 10/15/2024  

    Filename: student.php
   -->
    
   <title>Student</title>
   <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

	
  <link rel = "stylesheet" href="form.css">
  <link rel = "stylesheet" href="navigation.css">

</head>

<body>

<header>
<div class="topnav">
  <a class="active" href="home_page.html">Home</a>
  <a href="index.php">Peer Review Form</a>
  <a href="register.php">Register</a>
  <a href="faculty.php">Faculty</a>
  <a href="student.php">Student</a>
  <a href="login.php">Login</a>
</div>
</header>

    <h1>Student</h1>
	
		<form method="post">	
		
		<label for="peer">Pick a classmate to view your review for them</label>
			<select id="peer" name="peer">
				<option value="Hannah">Hannah</option>
				<option value="Josh">Josh</option>
				<option value="Piper">Piper</option>
			</select><br><br>
		<button type="submit" id = "submit" value="Submit">View Review</button>
		
	</form>
	<br><br>
	<form method="post" action="index.php"> <!-- Ensure action points to the correct file handling review submissions -->
    <label for="peer">Write a review for a classmate</label>
    <br>
    <input type="hidden" name="action" value="write_review"> <!-- Hidden field to identify action -->
    <button type="submit" id="submit" value="Submit">Write Review</button>
</form>

   <footer>
   </footer>
   
</body>
</html>