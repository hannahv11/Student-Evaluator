<?php
session_start();
include 'db_connection.php';
include 'active_user.php';


//checks if logged in user is faculty. If not you're brought back to login
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit;
}

//fetches created users from the db to write a review on. Must be student roles
$students = [];
$sql = "SELECT id, first_name, last_name FROM users WHERE role = 'student'";
$result = $pdo->query($sql);

if ($result) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $students[] = $row;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!--
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 10/15/2024  

    Filename: faculty.php
   -->
    
   <title>Faculty</title>
   <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
  <link rel = "stylesheet" href="form.css">
  <link rel = "stylesheet" href="navigation.css">

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


    <h1>Faculty</h1>
	
	<form action="generatePDF.php" method="post">	
		<label for="generate_reports">Pick a Student to Generate their PDF Report</label>
        <select id="student_reports" name="review_id" required>
            <option value="">Select a student...</option>
			<!-- Retrieves student users from db for selection-->
            <?php foreach ($students as $student): ?>
                <option value="<?php echo $student['id']; ?>">
                    <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
		<button type="submit" id = "generate_reports" value="generate_reports">Generate Report</button>
		
	</form>
	<br><br>
	
	<form action="view.php" method="post">	
		<label for="view_reviews">Pick a Student to View their Reviews</label>
        <select id="student_review" name="student_id" required>
            <option value="">Select a student...</option>
			<!-- Retrieves student users from db for selection-->
            <?php foreach ($students as $student): ?>
                <option value="<?php echo $student['id']; ?>">
                    <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
		<button type="submit" id = "view_reviews" value="view_reviews">View Reviews</button>
		
	</form>
	
	<br><br>
	<form method="post" action="generate_ids.php"> 
		<label for="generate_id">Generate the Student IDs into an Excel Spreadsheet</label>
		<br>
		<input type="hidden" name="action" value="generate_id"> 
		<button type="submit" id="generate_id" value="generate_id">Generate IDs</button>
	</form>

	<br><br>
	<form method="post" action="upload_teams.php"> 
		<label for="upload">Use an Excel Spreadsheet to Upload Groups</label>
		<br>
		<input type="hidden" name="action" value="upload"> 
		<button type="submit" id="upload" value="upload">Upload Groups</button>
	</form>
	
   <footer>
   </footer>
</body>
</html>
