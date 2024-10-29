<?php

session_start();

include("db_connection.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{


	// something was posted
	$username = $_POST['username'];
	$password = $_POST['password'];
	$role = $_POST['role'];
		
	if(!empty($username) && !empty($password) && !is_numeric($username))
	{
		// read from database
		$query = "select * from users where username = '$username' limit 1";

		$result = mysqli_query($con,$query);

		if($result)
		{
			if($result && mysqli_num_rows($result) > 0)
				{
					$user_data = mysqli_fetch_assoc($result);
					
					// validates if the information was correct and redirects to correct page
					if($user_data['password'] === $password)
					{
							$_SESSION['id'] = $user_data['id'];
							if($user_data['role'] === 'instructor') {
								header("Location: faculty.php");
								die;
							} 
							else {
								header("Location: student.php");
								die;
							}
					}
				}
		}
		echo "Wrong username or password";
		
	} else 
	{
		echo "Wrong username or password";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
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

<body>

	<div id="login">
		<form method="post">
			<h1>Login</h1>
			
		<label for="username">Username:</label>
			<input id="text" type="text" name="username"><br><br>
			
		<label for="password">Password:</label>
			<input id="text" type="password" name="password"><br><br>

			<input id="button" type="submit" value="Login"><br><br>

			<a href="signup.php">Click to Signup</a><br><br>
		</form>
	</div>

</body>
</html>
