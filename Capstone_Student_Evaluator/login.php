<?php

session_start();

include("db_connection.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{


	// something was posted
	$username = $_POST['username'];
	$password = $_POST['password'];
		
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
					
					if($user_data['password'] === $password)
					{
						$_SESSION['id'] = $user_data['id'];
						header("Location: index.html");
						die;
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
</head>

<body>

	<div id="login">
		<form method="post">
			<h1>Login</h1>

			<input id="text" type="text" name="username"><br><br>
			<input id="text" type="password" name="password"><br><br>

			<input id="button" type="submit" value="Login"><br><br>

			<a href="signup.php">Click to Signup</a><br><br>
		</form>
	</div>

</body>
</html>