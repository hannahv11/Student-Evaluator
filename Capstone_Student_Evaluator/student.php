<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

// Debug line to verify session contents (remove or comment out in production)
//echo "Session ID: " . (isset($_SESSION['id']) ? $_SESSION['id'] : "Not set") . " | Role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : "Not set") . "<br>";

//checks if current login is student role
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
   //sends you back to login if not, or logged in as faculty
   header("Location: login.php");
   exit;
}

//Retrieves the success message upon submitting review
$submissionMessage = isset($_GET['success']) && $_GET['success'] === 'review_submitted' ? 'Review successfully submitted!' : '';
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
   <link rel="stylesheet" href="form.css">
   <link rel="stylesheet" href="navigation.css">
   <link rel="stylesheet" href="mobile.css">
   <style>
       .message { /* Submission message styling */
           margin: 50px auto;
           text-align: center;
           font-size: 1.5em;
           font-weight: bold;
           color: darkgreen;
       }
   </style>
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

<h1>Student</h1>

<!-- displays submission message -->
<?php if ($submissionMessage): ?>
    <div class="message">
        <?php echo $submissionMessage; ?>
    </div>
<?php endif; ?>

<form method="post" action="student_view.php">	
    <label for="view">View Past Reviews You Have Written</label>
    <br>
    <input type="hidden" name="action" value="view_review">
    <button type="submit" id="view" value="view">View Reviews</button>	
</form>

<br><br>
<form method="post" action="index.php"> 
    <label for="submit">Write A Review For A Classmate</label>
    <br>
    <input type="hidden" name="action" value="write_review"> 
    <button type="submit" id="submit" value="Submit">Write Review</button>
</form>

<footer>
</footer>
</body>
</html>
