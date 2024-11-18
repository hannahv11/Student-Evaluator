<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

//check if user is logged in as a student
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

//gets the student data based on login session
$student_id = $_SESSION['id'];

//gets all reviews written by student
$stmt_reviews = $pdo->prepare("SELECT * FROM submissions WHERE student_id = :student_id");
$stmt_reviews->execute(['student_id' => $student_id]);
$reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);

//student name that was reviewed
function getReviewedStudentName($pdo, $reviewed_id) {
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = :id");
    $stmt->execute(['id' => $reviewed_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Past Reviews</title>
    <link rel="stylesheet" href="navigation.css">
	<link rel="stylesheet" href="view.css">
    <link rel = "stylesheet" href="mobile.css">
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

<article>
<h1>Your Past Reviews</h1>

<?php if (empty($reviews)): ?>
    <p>You have not written any reviews yet.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Reviewed Student</th>
                <th>Review ID</th>
                <th>Avg Rating</th>
                <th>Q1 Score</th>
                <th>Q2 Score</th>
                <th>Q3 Score</th>
                <th>Q4 Score</th>
                <th>Q5 Score</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
                <?php
                $reviewed_student = getReviewedStudentName($pdo, $review['review_id']);
                $reviewed_name = htmlspecialchars($reviewed_student['first_name'] . ' ' . $reviewed_student['last_name']);
                $average_rating = ($review['q1_rating'] + $review['q2_rating'] + $review['q3_rating'] + $review['q4_rating'] + $review['q5_rating']) / 5;
                ?>
                <tr>
                    <td><?php echo $reviewed_name; ?></td>
                    <td><?php echo htmlspecialchars($review['id']); ?></td>
                    <td><?php echo number_format($average_rating, 2); ?></td>
                    <td><?php echo htmlspecialchars($review['q1_rating']); ?></td>
                    <td><?php echo htmlspecialchars($review['q2_rating']); ?></td>
                    <td><?php echo htmlspecialchars($review['q3_rating']); ?></td>
                    <td><?php echo htmlspecialchars($review['q4_rating']); ?></td>
                    <td><?php echo htmlspecialchars($review['q5_rating']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="comments">
        <h2>Comments from Your Reviews</h2>
        <?php foreach ($reviews as $review): ?>
            <?php
            $reviewed_student = getReviewedStudentName($pdo, $review['review_id']);
            $reviewed_name = htmlspecialchars($reviewed_student['first_name'] . ' ' . $reviewed_student['last_name']);
            ?>
            <h3>Review ID: <?php echo htmlspecialchars($review['id']); ?></h3>
            <p><strong>Reviewed Student:</strong> <?php echo $reviewed_name; ?></p>
            <p><strong>Comments:</strong></p>
            <ul>
                <li>Team member participated in team meetings/online discussions: <?php echo htmlspecialchars($review['q1_comment']); ?></li>
                <li>Team member assignments were handed in, in a timely manner: <?php echo htmlspecialchars($review['q2_comment']); ?></li>
                <li>Team member produced quality work: <?php echo htmlspecialchars($review['q3_comment']); ?></li>
                <li>Group interaction was professional and respectful: <?php echo htmlspecialchars($review['q4_comment']); ?></li>
                <li>Team member willingly engaged: <?php echo htmlspecialchars($review['q5_comment']); ?></li>
            </ul>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</article>

</body>
</html>
