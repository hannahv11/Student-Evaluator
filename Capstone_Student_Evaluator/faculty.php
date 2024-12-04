<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

//checks if logged in user is faculty.
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'instructor') {
    //brought back to login if not
    header("Location: login.php");
    exit;
}

// Process the password update form into Database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    //retrieves current admin password
    $admin_query = "SELECT password FROM users WHERE id = 1";
    $stmt = $pdo->prepare($admin_query);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        //checks if old entered pass is correct
        if (!password_verify($old_password, $admin['password'])) 
        {
            echo "Old password entered is incorrect.";
        } 
        elseif ($new_password !== $confirm_password) 
        {
            echo "New password does not match confirmation. Please try again";
        } 
        elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $new_password)) 
        {
            echo "New password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.";
        } 
        else 
        {
            //hashes the new password and update it in the database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = ? WHERE id = 1";
            $update_stmt = $pdo->prepare($update_query);

            if ($update_stmt->execute([$hashed_password])) 
            {
                echo "Password updated successfully.";
            } 
            else 
            {
                echo "Error updating password.";
            }
        }
    } 
    else 
    {
        echo "Admin account not found.";
    }
}

//function for resetting teams table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_teams'])) {
    try {
        $delete_query = "DELETE FROM teams";
        $pdo->exec($delete_query);
        echo "All existing group assignments have been reset";
    } catch (PDOException $e) { //throws exception in case of error
        echo "Error performing reset: " . $e->getMessage();
    }
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student_id'])) {
    $delete_student_id = $_POST['delete_student_id'];

    try {
        $pdo->beginTransaction();

        //removes student from the users table
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :student_id");
        $stmt->execute(['student_id' => $delete_student_id]);

        //removes student from submissions table (their reviews)
        $stmt = $pdo->prepare("DELETE FROM submissions WHERE student_id = :student_id");
        $stmt->execute(['student_id' => $delete_student_id]);

        //removes student from the teams table
        $stmt = $pdo->prepare("DELETE FROM teams WHERE student_id = :student_id");
        $stmt->execute(['student_id' => $delete_student_id]);

        //performs action
        $pdo->commit();

        //shows success message and refreshes page upon deletion.
        header("Location: faculty.php?message=Student+with+ID+{$delete_student_id}+deleted+from+database");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error deleting account data: " . $e->getMessage();
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

    <h1>Faculty Dashboard</h1>
    <br><br>

    <!-- section for password change -->
        <h2 style="text-align: center;">Change Administrator Password</h2>
            <form method="post">
                <label for="old_password">Old Password:</label>
                    <input type="password" id="old_password" name="old_password" required>

                <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" 
                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" 
                        title="Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character." 
                        required>

                <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required><br><br>
                    <button type="submit" name="update_password">Update Password</button>
            </form>
            <br><br>
    

    <!-- Review and PDF management -->
        <h2 style="text-align: center;">PDF and Review Management</h2>
            <form action="generatePDF.php" method="post">	
                <label for="generate_reports">Pick a Student to Generate their PDF Report:</label>
                    <select id="student_reports" name="review_id" required>
                        <option value="">--Select a student--</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>">
                                <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br><br>
                    <button type="submit" id="generate_reports" value="generate_reports">Generate Report</button>
            </form>

        <!-- View student reviews -->
            <form action="view.php" method="post">	
                <label for="view_reviews">Pick a Student to View their Reviews:</label>
                    <select id="student_review" name="student_id" required>
                        <option value="">--Select a student--</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>">
                                <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br><br>
                <button type="submit" id="view_reviews" value="view_reviews">View Reviews</button>
            </form>
            <br><br>

    <!-- Group Management Section -->
        <h2 style="text-align: center;">Group Management</h2>
            <form method="post" action="generate_ids.php"> 
                <label for="generate_id">Generate the Student IDs into an Excel Spreadsheet:</label><br>
                <button type="submit" id="generate_id" value="generate_id">Generate IDs</button>
            </form>

            <form method="post" action="upload_teams.php"> 
                <label for="upload">Use an Excel Spreadsheet to Upload Groups:</label><br>
                <button type="submit" id="upload" value="upload">Upload Groups</button>
            </form>
            <br><br>

    <!-- Student Account Management Section -->
        <h2 style="text-align: center;">Student Management</h2>
            <form method="post">
                <label for="reset_teams">Reset all current group assignments:</label>
                <button type="submit" name="reset_teams" onclick="return confirm('This will reset all current team assignments. A new excel assignment table will need to be uploaded for new assignments. This action cannot be undone.')">Reset Teams</button>
            </form>

            <form method="post">
                <label for="delete_student_id">Select a student to delete their account:</label>
                    <select id="delete_student_id" name="delete_student_id" required>
                        <option value="">--Select a student--</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo htmlspecialchars($student['id']); ?>">
                                <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br><br>
                <button type="submit" name="delete_student" onclick="return confirm('This will delete all associated information of this Student. This action cannot be undone.')">Delete Student</button>
            </form>
            <br><br>
    <footer>
    </footer>
</body>
</html>
