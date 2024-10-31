<?php
session_start();
include 'db_connection.php';

//for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

//check if logged in
if (!isset($_SESSION['id'])) {
    echo "Error: User is not logged in.";
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
        echo "Error: Invalid review ID selected.";
        exit;
    }

    //inserts data via SQL into DB
    $sql = "INSERT INTO submissions 
        (review_id, student_id, q1_rating, q1_comment, q2_rating, q2_comment, q3_rating, q3_comment, q4_rating, q4_comment, q5_rating, q5_comment) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //uses PHP data objects for submission
    $stmt = $pdo->prepare($sql);    //'prepare' prevents SQL injections
    $stmt->bindParam(1, $review_id, PDO::PARAM_INT);    //binds variables to parameters
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
    //runs SQL statement with bound parameters
    if ($stmt->execute()) {
        echo "Review submitted.";
    } else {
        echo "Error: could not submit review properly.";
    }
    //error handling
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>