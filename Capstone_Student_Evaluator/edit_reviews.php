<?php
session_start();
include 'db_connection.php';
include 'active_user.php';


// Check if the user is logged in as a student
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    // Redirect to login if not logged in as a student
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['id'];  // Get the logged-in student's ID
$review_id = $_GET['review_id'];  // Get the review ID to edit

// Fetch the review to edit
$query = "SELECT * FROM submissions WHERE review_id = ? AND student_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$review_id, $student_id]);

$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    echo "Review not found or you're not authorized to edit this review.";
    exit;
}

// If the form is submitted, update the review
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated data from the form
    $q1_rating = $_POST['Q1'];
    $q1_comment = $_POST['Q1TB'];
    $q2_rating = $_POST['Q2'];
    $q2_comment = $_POST['Q2TB'];
    $q3_rating = $_POST['Q3'];
    $q3_comment = $_POST['Q3TB'];
    $q4_rating = $_POST['Q4'];
    $q4_comment = $_POST['Q4TB'];
    $q5_rating = $_POST['Q5'];
    $q5_comment = $_POST['Q5TB'];

    // Update the review in the database
    $update_query = "UPDATE submissions SET 
                        q1_rating = ?, q1_comment = ?, q2_rating = ?, q2_comment = ?, 
                        q3_rating = ?, q3_comment = ?, q4_rating = ?, q4_comment = ?, 
                        q5_rating = ?, q5_comment = ? 
                     WHERE review_id = ? AND student_id = ?";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->execute([
        $q1_rating, $q1_comment, $q2_rating, $q2_comment, 
        $q3_rating, $q3_comment, $q4_rating, $q4_comment, 
        $q5_rating, $q5_comment, $review_id, $student_id
    ]);

    // Redirect back to the list of reviews after updating
header("Location: student.php?success=review_updated");
exit;

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="navigation.css">
    <link rel="stylesheet" href="mobile.css">
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

<h1>Edit Your Review</h1>

<form method="post">
    <label for="Q1">Team member participated in team meetings/online discussions:</label><br>
    <select id="Q1" name="Q1">
        <?php for ($i = 1; $i <= 20; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $review['q1_rating'] ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <textarea id="Q1TB" name="Q1TB" rows="4" cols="50"><?= htmlspecialchars($review['q1_comment']) ?></textarea><br>      

    <label for="Q2">Team member assignments were handed in, in a timely manner:</label><br>
    <select id="Q2" name="Q2">
        <?php for ($i = 1; $i <= 20; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $review['q2_rating'] ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <textarea id="Q2TB" name="Q2TB" rows="4" cols="50"><?= htmlspecialchars($review['q2_comment']) ?></textarea><br>

    <label for="Q3">Team member produced quality work:</label><br>
    <select id="Q3" name="Q3">
        <?php for ($i = 1; $i <= 20; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $review['q3_rating'] ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <textarea id="Q3TB" name="Q3TB" rows="4" cols="50"><?= htmlspecialchars($review['q3_comment']) ?></textarea><br>

    <label for="Q4">Group interaction was professional and respectful:</label><br>
    <select id="Q4" name="Q4">
        <?php for ($i = 1; $i <= 20; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $review['q4_rating'] ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <textarea id="Q4TB" name="Q4TB" rows="4" cols="50"><?= htmlspecialchars($review['q4_comment']) ?></textarea><br>

    <label for="Q5">Team member willingly engaged:</label><br>
    <select id="Q5" name="Q5">
        <?php for ($i = 1; $i <= 20; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $review['q5_rating'] ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <textarea id="Q5TB" name="Q5TB" rows="4" cols="50"><?= htmlspecialchars($review['q5_comment']) ?></textarea><br>

    <button type="submit" value="Update Review">Update Review</button>
</form>
</body>
</html>
