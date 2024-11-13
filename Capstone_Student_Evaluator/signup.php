<?php
session_start();
include("db_connection.php"); 
include 'active_user.php';

// include("functions.php"); where to utilize?

// Checks to see that the admin hasn't been created already
$check_admin_stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$check_admin_stmt->execute([1]);
$admin_check_result = $check_admin_stmt->fetch(PDO::FETCH_ASSOC);

// creates the admin account if not
if(!$admin_check_result) {
    $admin_first_name = 'Admin';
    $admin_last_name = '';
    $admin_user = 'admin';
    $admin_password = 'Password123!'; // change this later, or add a way to change password
    $role = 'instructor';
    $admin_id = 1;
    
    $hashed_admin_pass = password_hash($admin_password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (id, first_name, last_name, username, password, role) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query); 
                //edited this to gel with PDO and password hashing in connection/submission script -HV
                if ($stmt->execute([$admin_id, $admin_first_name, $admin_last_name, $admin_user, $hashed_admin_pass, $role])) {
                    header("Location: signup.php");
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Gets data from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'student';

    if (!empty($first_name) && !empty($last_name) && !empty($username) && !empty($password) && !is_numeric($username)) {
       
        // Checks to see that the username is unique
        $check_username_stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $check_username_stmt->execute([$username]);
        $result = $check_username_stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo "Please choose another username. Username is already taken";
        } else {
            // Generate an id from numbers 10-99
            do {
                $id = mt_rand(10, 99);
                $check_id_stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $check_id_stmt->execute([$id]);
                $result = $check_id_stmt->fetch(PDO::FETCH_ASSOC);
            } while ($result);

            // Insert data into the database
            //modified to include password hashing for more security.
            //Passwords can't be viewed in database anymore so write them down
            //if you need to for testing! :) -HV
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (id, first_name, last_name, username, password, role) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query); 
            //edited this to gel with PDO and password hashing in connection/submission script -HV
            if ($stmt->execute([$id, $first_name, $last_name, $username, $hashed_password, $role])) {
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            //closes statement check
            $stmt->close();
        }
        //closes username check
        $check_username_stmt->close();
    //kills program if info isn't valid    
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
  <a href="index.php">Peer Review Form</a>
  <a href="signup.php">Register</a>
  <a href="faculty.php">Faculty</a>
  <a href="student.php">Student</a>
  <a href="login.php">Login</a>
  <a href="logout.php">Logout</a>

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

			<input id="button" type="submit" name="submit" value="Sign Up"><br><br>

			<a href="login.php">Click to Log in</a><br><br>
		</form>
	</div>

</body>
</html>
