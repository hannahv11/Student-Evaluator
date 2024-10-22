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
			// save to database
			$id = random_num(20);
			$query = "insert into users (id,username,password,role) values ('$id','$username','$password','$role')";

			mysqli_query($con,$query);

			header("Location: login.php");
			die;
		} else {
			echo "Please enter some valid information!";
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
</head>

<body>

	<div id="box">
        
		<form method="post">
			<h1>Sign Up</h1>

			<input id="text" type="text" name="username"><br><br>
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