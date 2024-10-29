<?php
session_start();
include("db_connection.php");
//include ("functions.php"); unsure on where to utilize

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Gets data from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($first_name) && !empty($last_name) && !empty($username) && !empty($password) && !is_numeric($username)) {
       
	   //checks to see that the username is unique
        $check_username_stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
        $check_username_stmt->bind_param("s", $username);
        $check_username_stmt->execute();
        $result = $check_username_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Please choose another username. Username is already taken";
		}else{
	   // Generate an id from numbers 10-99
        do {
            $id = mt_rand(10, 99);
            $check_id = $con->prepare("SELECT * FROM users WHERE id = ?");
            $check_id->bind_param("i", $id);
            $check_id->execute();
            $result = $check_id->get_result();
        } while ($result->num_rows > 0);
		//closes id check
        $check_id->close();

        // Insert data into the database
        $query = "INSERT INTO users (id, first_name, last_name, username, password, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("isssss", $id, $first_name, $last_name, $username, $password, $role);

//navigates user to login page if it successfully adds to the database
        if ($stmt->execute()) {
            header("Location: login.php");
            die;
        } else {
            echo "Error: " . $stmt->error;
        }
		//closes statement check
		$stmt->close();
		}
		//closes username check
        $check_username_stmt->close();
		
 //kills program if info is not valid       
    } else {
        echo "Please enter some valid information!";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
	<link rel = "stylesheet" href="navigation.css">
	<link rel = "stylesheet" href="signup.css">


</head>

<body>

<div class="topnav">
  <a class="active" href="home_page.html">Home</a>
  <a href="index.html">Peer Review Form</a>
  <a href="signup.php">Register</a>
  <a href="faculty.php">Faculty</a>
  <a href="student.php">Student</a>
  <a href="login.php">Login</a>
</div>
</header>

<!-- This code has users enter info to add it to the database and has them signup to use the peer review-->
	<div id="box">
        
		<form method="post">
			<h1>Sign Up</h1>
		
		<label for="first_name">First Name:</label>
	       <input id="text" type="text" name="first_name"><br><br>
		
		<label for="last_name">Last Name:</label>
			<input id="text" type="text" name="last_name"><br><br>
			
        <label for="username">Username:</label>
			<input id="text" type="text" name="username"><br><br>
		
		<label for="password">Password:</label>
			<input id="text" type="password" name="password"><br><br>
		 
        <input id="student" type="radio" name="role" value="student" checked>
            <label for="student">Student</label><br>
            
		<input id="instructor" type="radio" name="role" value="instructor">
            <label for="instructor">Instructor</label><br><br>

			<input id="button" type="submit" name="submit" value="Sign Up"><br><br>

			<a href="login.php">Click to Log in</a><br><br>
		</form>
	</div>

</body>
</html>
