<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

//checks if current login is student role
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    // Sends you back to login if not logged in as student or logged in as faculty
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['id'];

//if already assigned a team will fetch the team info
$currentTeamQuery = "SELECT team_name FROM teams WHERE student_id = ?";
$stmt = $pdo->prepare($currentTeamQuery);
$stmt->execute([$student_id]);
$currentTeam = $stmt->fetch(PDO::FETCH_ASSOC);

//gets all available teams present in teams table (created from faculty excel file upload)
$availableTeamsQuery = "SELECT DISTINCT team_id, team_name FROM teams";
$stmt = $pdo->prepare($availableTeamsQuery);
$stmt->execute();
$availableTeams = $stmt->fetchAll(PDO::FETCH_ASSOC);

//shows respective success or fail messages for student joining respective team/group if necessary
$teamMessage = '';
if (isset($_GET['success']) && $_GET['success'] === 'team_joined') {
    $teamMessage = 'You successfully joined a group!';
} elseif (isset($_GET['error'])) {
    $teamMessage = match ($_GET['error']) {
        'already_in_team' => 'You are already part of a group.',
        'team_not_found' => 'The group you selected does not exist.',
        'team_join_failed' => 'Failed to join the group. Please try again.',
        default => ''
    };
}

//Retrieves the success message upon submitting review
$submissionMessage = isset($_GET['success']) && $_GET['success'] === 'review_submitted' ? 'Review successfully submitted!' : '';

//Error message for existing review
$errorMessage = '';
if (isset($_GET['error']) && $_GET['error'] === 'review_exists') {
    $errorMessage = 'You have already submitted a review for this classmate.';
}

//Fetch reviews written by the student
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

<h1>Student Menu</h1>

<!-- Displays group messages -->
<?php if ($teamMessage): ?>
    <div class="message <?= isset($_GET['success']) ? 'success' : 'error' ?>">
        <?= $teamMessage ?>
    </div>
<?php endif; ?>

<!-- Displays the student's current group -->
<?php if ($currentTeam): ?>
    <p>You are currently part of group: <?= htmlspecialchars($currentTeam['team_name']) ?></p>
<?php else: ?>
    <p>You do not belong to a group yet. Please join a group in order to write reviews.</p>
    <?php if ($availableTeams): ?>
        <!-- form to join a group if not already assigned -->
        <form method="post" action="join_team.php">
            <label for="team">Select a Group:</label>
            <select id="team" name="team_id" required>
                <option value="">--Select a Group--</option>
                <?php foreach ($availableTeams as $team): ?>
                    <option value="<?= htmlspecialchars($team['team_id']) ?>">
                        <?= htmlspecialchars($team['team_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">Join Group</button>
        </form>
    <?php else: ?>
        <p>There are no Groups available right now. Check back later when updated or contact your instructor.</p>
    <?php endif; ?>
<?php endif; ?>

<br><br>

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
        <button type="submit">Edit Selected Review</button>
    </form>
<?php endif; ?>

</body>
</html>