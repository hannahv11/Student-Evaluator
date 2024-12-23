<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

//check if user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
	//Redirect if not logged in. Page only accessible if logged in.
    header("Location: login.php");
    exit;
}

//finds users team_id through the teams table in DB.
//Uses SQL Query to match team ID to student ID
$user_id = $_SESSION['id'];
$team_id_search = "SELECT team_id FROM teams WHERE student_id = :user_id";
$team_stmt = $pdo->prepare($team_id_search);
$team_stmt->execute(['user_id' => $user_id]);
$user_team = $team_stmt->fetch(PDO::FETCH_ASSOC);

//Fetches students within same team as the user making a review
//Displays students within team as an array. Students within same team
//can now only appear in dropdown selection for reviewing
//Excludes the already logged in user
$students = [];
if ($user_team) {
    $team_id = $user_team['team_id'];
    $sql = "SELECT users.id, users.first_name, users.last_name
            FROM users
            JOIN teams ON users.id = teams.student_id
            WHERE users.role = 'student' 
              AND teams.team_id = :team_id 
              AND users.id != :loggedInId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'team_id' => $team_id,
        'loggedInId' => $_SESSION['id'],
    ]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!--
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 10/15/2024  

    Filename: index.php
   -->
    <title>Peer Review</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="navigation.css">
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
    	<h1>Peer Review</h1>
			<p>Please fill out this peer review form for each one of your team members.</p>
			<p>Grade your teammate for each question on a scale from 1 to 20 in the drop-down list. <br> Please also add any relavent comments based on the chosen rating.</p>

			<form method="post" action="submission_script.php">
				<input type="hidden" name="student_id" value="<?php echo $_SESSION['id']; ?>"> <!-- Set student ID from session -->
				<label for="peer">Pick a team member for review</label>
				<select id="peer" name="review_id" required>
					<option value="">--Select a student--</option>
					<!-- Retrieves student users from db for selection-->
					<?php foreach ($students as $student): ?>
						<option value="<?php echo $student['id']; ?>">
							<?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
						</option>
					<?php endforeach; ?>
        		</select><br><br>
		
			<label for="Q1">Team member participated in team meetings/online discussions:</label><br>
			<select id="Q1" name="Q1">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
			</select>
			<textarea maxlength = "450" id="Q1TB" name="Q1TB" rows="4" cols="50"></textarea><br>	 
			
			<label for="Q2">Team member assignments were handed in, in a timely manner:</label><br>
			<select id="Q2" name="Q2">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
			</select>
			<textarea maxlength = "450" id="Q2TB" name="Q2TB" rows="4" cols="50"></textarea><br>
			
			<label for="Q3">Team member produced quality work:</label><br>
			<select id="Q3" name="Q3">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
			</select>
			<textarea maxlength = "450" id="Q3TB" name="Q3TB" rows="4" cols="50"></textarea><br>
			
			<label for="Q4">Group interaction was professional and respectful:</label><br>
			<select id="Q4" name="Q4">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
			</select>
			<textarea maxlength = "450" id="Q4TB" name="Q4TB" rows="4" cols="50"></textarea><br>

			
			<label for="Q5">Team member willingly engaged:</label><br>
			<select id="Q5" name="Q5">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
			</select>
			<textarea maxlength = "450" id="Q5TB" name="Q5TB" rows="4" cols="50"></textarea><br>
			
			<button type="reset" value="Reset">Reset</button>
			<button type="submit" id = "submit" value="Submit">Submit</button>

			</form>
   <footer>
   </footer>
</body>
</html>
