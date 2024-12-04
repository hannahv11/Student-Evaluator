<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

//ensures user is logged in
if (!isset($_SESSION['id'])) {
    echo "Error: User not logged in.";
    exit;
}

$review_id = $_POST['review_id']; //Set by the dropdown in index.php
$student_id = $_SESSION['id']; //Set from current logged-in session

//retrieves users posted ratings and comments
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

try {
    // Ensure review_id exists in the users table
    $review_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
    $review_check->execute([$review_id]);
    
    if ($review_check->fetchColumn() == 0) {
        header("Location: student.php?error=invalid_review_id");
        exit;
    }

    //checks if the review already exists for the selected student
    $checkReviewQuery = "SELECT COUNT(*) FROM submissions WHERE student_id = ? AND review_id = ?";
    $stmt = $pdo->prepare($checkReviewQuery);
    $stmt->execute([$student_id, $review_id]);

    if ($stmt->fetchColumn() > 0) {
        //If review already exists, redirect back to student.php with a message
        header("Location: student.php?error=review_exists");
        exit;
    }

    //prepares new review to be submitted into submissions table through assigned values
    $sql = "INSERT INTO submissions 
        (review_id, student_id, q1_rating, q1_comment, q2_rating, q2_comment, q3_rating, q3_comment, q4_rating, q4_comment, q5_rating, q5_comment) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    //binds new values into submissions table
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $review_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $student_id, PDO::PARAM_INT);
    $stmt->bindParam(3, $q1_rating, PDO::PARAM_INT);
    $stmt->bindParam(4, $q1_comment, PDO::PARAM_STR);
    $stmt->bindParam(5, $q2_rating, PDO::PARAM_INT);
    $stmt->bindParam(6, $q2_comment, PDO::PARAM_STR);
    $stmt->bindParam(7, $q3_rating, PDO::PARAM_INT);
    $stmt->bindParam(8, $q3_comment, PDO::PARAM_STR);
    $stmt->bindParam(9, $q4_rating, PDO::PARAM_INT);
    $stmt->bindParam(10, $q4_comment, PDO::PARAM_STR);
    $stmt->bindParam(11, $q5_rating, PDO::PARAM_INT);
    $stmt->bindParam(12, $q5_comment, PDO::PARAM_STR);

    if ($stmt->execute()) {
        //redirects to student.php instead of a plain text message
        header("Location: student.php?success=review_submitted");
        exit;
    } else { //throws error if submission failed
        header("Location: student.php?error=submit_failed");
        exit;
    }
} catch (PDOException $e) { //exception handling
    header("Location: student.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>
