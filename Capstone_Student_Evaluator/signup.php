<?php
session_start();
include 'db_connection.php'; 
include 'active_user.php';

//Checks database to see if admin account exists already. Searches for ID '1'
$check_admin_stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$check_admin_stmt->execute([1]);
$admin_check_result = $check_admin_stmt->fetch(PDO::FETCH_ASSOC);

//If admin doesn't exist, creates the account with default values
if (!$admin_check_result) {
    $admin_first_name = 'Admin';
    $admin_last_name = '';
    $admin_user = 'admin';
    $admin_password = 'Password123!';
    $role = 'instructor';
    $admin_id = 1;

    //hashes password in database for security
    $hashed_admin_pass = password_hash($admin_password, PASSWORD_DEFAULT);

    //inserts admin account values via SQL into database
    $query = "INSERT INTO users (id, first_name, last_name, username, password, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$admin_id, $admin_first_name, $admin_last_name, $admin_user, $hashed_admin_pass, $role]);
}

//handles form submission upon post action
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //retrieves entered data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'student'; //only able to sign up as a student

    //validates if all forms are filled out
    if (!empty($first_name) && !empty($last_name) && !empty($username) && !empty($password) && !empty($confirm_password)) {

        //checks if passwords match
        if ($password !== $confirm_password) {
            echo "Entered passwords do not match, please try again.";
        } 

        //validates required password strength
        elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
            echo "Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.";
        } 
                
        else {
            //checks database if username wasn't already created
            $check_username_stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $check_username_stmt->execute([$username]);

            if ($check_username_stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "Username is already taken. Please choose another.";
            } 
                    
            else {
                //generates unique ID for signed up student
                do 
                {
                    $id = mt_rand(10, 99);
                    $check_id_stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                    $check_id_stmt->execute([$id]);
                } 
                while ($check_id_stmt->fetch(PDO::FETCH_ASSOC));

                //hashes created password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                //Inserts created user into database
                $query = "INSERT INTO users (id, first_name, last_name, username, password, role) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);

                if ($stmt->execute([$id, $first_name, $last_name, $username, $hashed_password, $role])) {
                //Stores success message upon account creation to display on login.php once signed up
                $_SESSION['signup_success'] = "Account successfully created. Please log in.";
    
                //redirects to login.php
                header("Location: login.php");
                exit();
                } 
                //throws error if applicable
                else {
                    echo "An error occurred while creating your account. Please try again.";
                }

            }
        }
    } 

    else {
            echo "Please fill in all the fields."; //if any field isn't filled out
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!--
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 10/15/2024  

    Filename: signup.php
   -->
    <title>Signup</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="stylesheet" href="navigation.css">
            <link rel="stylesheet" href="signup.css">
            <link rel="stylesheet" href="mobile.css">
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

<!-- Signup Form -->
    <div id="box">
        <form method="post">
            <h1>Sign Up</h1>
                <label for="first_name">First Name:</label>
                    <input id="first_name" type="text" name="first_name" required><br><br>
                <label for="last_name">Last Name:</label>
                    <input id="last_name" type="text" name="last_name" required><br><br>
                <label for="username">Username:</label>
                    <input id="username" type="text" name="username" required><br><br>
                <label for="password">Password:</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" 
                        title="Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character." 
                        required>
                    <br><br>
                <label for="confirm_password">Confirm Password:</label>
                    <input id="confirm_password" type="password" name="confirm_password" required><br><br>
                    <input id="button" type="submit" name="submit" value="Sign Up"><br><br>
                <a href="login.php">Click to Log in</a><br><br>
        </form>
    </div>
</body>
</html> 
