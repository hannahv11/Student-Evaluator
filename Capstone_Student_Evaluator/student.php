<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

// Debug line to verify session contents (remove or comment out in production)
// echo "Session ID: " . (isset($_SESSION['id']) ? $_SESSION['id'] : "Not set") . " | Role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : "Not set") . "<br>";

// Checks if current login is student role
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    // Sends you back to login if not logged in as student or logged in as faculty
    header("Location: login.php");
    exit;
}

// Retrieves the success message upon submitting review
$submissionMessage = isset($_GET['success']) && $_GET['success'] === 'review_submitted' ? 'Review successfully submitted!' : '';

// Error message for existing review
$errorMessage = '';
if (isset($_GET['error']) && $_GET['error'] === 'review_exists') {
    $errorMessage = 'You have already submitted a review for this classmate.';
}

// Fetch reviews written by the student
$student_id = $_SESSION['id'];
$query = "SELECT submissions.*, users.first_name AS reviewed_first_name, users.last_name AS reviewed_last_name
          FROM submissions
          JOIN users ON submissions.review_id = users.id
          WHERE submissions.student_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$student_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
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

       .error { /* Error message styling */
           margin: 50px auto;
           text-align: center;
           font-size: 1.5em;
           font-weight: bold;
           color: rgb(215, 71, 63);
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

<!-- Display error message if review already exists -->
<?php if ($errorMessage): ?>
    <div class="error">
        <?php echo $errorMessage; ?>
    </div>
<?php endif; ?>

<!-- Button to write a new review -->
<form method="post" action="index.php">
    <label for="submit">Write A Review For A Classmate</label>
    <br>
    <input type="hidden" name="action" value="write_review">
    <button type="submit" id="submit" value="Submit">Write Review</button>
</form>

<br><br>

<!-- Form to select and view past reviews -->
<?php if ($reviews): ?>
    <form method="get" action="edit_reviews.php">
        <label for="review">Edit Past Reviews You Have Written</label><br>
        <select id="review" name="review_id" required>
            <option value="">--Select a review--</option>
            <?php foreach ($reviews as $review): ?>
                <option value="<?= $review['review_id'] ?>">
                    <?= htmlspecialchars($review['reviewed_first_name'] . ' ' . $review['reviewed_last_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
    <button type="submit" value="Edit Selected Review">Edit Selected Review</button>
    </form>
<?php endif; ?>

</body>
</html>
