<?php
session_start();
include 'db_connection.php';

//for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

//check if logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php?error=not_logged_in");
    exit;
}

//retrieves values from submitted form
$review_id = $_POST['review_id']; //set by the dropdown in index.php
$student_id = $_SESSION['id']; //set from current logged in session

//retrieves ratings and comments
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
    //Ensure review_id exists in the users table
    $review_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
    $review_check->execute([$review_id]);
    
    if ($review_check->fetchColumn() == 0) {
        header("Location: student.php?error=invalid_review_id");
        exit;
    }

    //inserts data via SQL into DB
    $sql = "INSERT INTO submissions 
        (review_id, student_id, q1_rating, q1_comment, q2_rating, q2_comment, q3_rating, q3_comment, q4_rating, q4_comment, q5_rating, q5_comment) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
    } else {
        header("Location: student.php?error=submit_failed");
        exit;
    }
} catch (PDOException $e) {
    header("Location: student.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>