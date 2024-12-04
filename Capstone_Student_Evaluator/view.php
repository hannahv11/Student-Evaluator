<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

// user login
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit;
}

//Gets the student name from the faculty.php
$student_id = $_POST['student_id'];
$stmt_student = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = :id");
$stmt_student->execute(['id' => $student_id]);
$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

// Checks that student can be found
if (!$student) {
    echo "Student not found.";
    exit;
}

//Reviews from that student
$stmt_reviews = $pdo->prepare("SELECT * FROM submissions WHERE student_id = :student_id");
$stmt_reviews->execute(['student_id' => $student_id]);
$reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);

//Gets name of student that wrote the reviews
function getReviewedStudentName($pdo, $reviewed_id) {
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = :id");
    $stmt->execute(['id' => $reviewed_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 10/15/2024  

    Filename: view.php
   -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reviews Written by <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></title>
            <link rel = "stylesheet" href="navigation.css">
            <link rel = "stylesheet" href="view.css">
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
    <article> <!--Fetches selected students name -->
    <h1>Reviews Written by <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h1>
        <?php if (empty($reviews)): ?> 
            <p>No reviews found for this student.</p>
        <?php else: ?> <!--Displays student's review and values -->
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
                    <?php //retrieves ratings to appropriate review from database
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
            <h2>Comments from Reviews</h2>
            <?php foreach ($reviews as $review): ?>
                <?php //retrieves comments and displays text
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
