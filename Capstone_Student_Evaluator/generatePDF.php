<?php
session_start();
include 'db_connection.php';
require('fpdf/fpdf.php'); 

// User login
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit;
}

// gets the name of the student
$review_id = $_POST['review_id'];
$stmt_student = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = :id");
$stmt_student->execute(['id' => $review_id]);
$student = $stmt_student->fetch(PDO::FETCH_ASSOC);


//Gets the peer reviews for the student
$stmt_reviews = $pdo->prepare("SELECT * FROM submissions WHERE review_id = :review_id");
$stmt_reviews->execute(['review_id' => $review_id]);
$reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);

//gets the name of the person reviewing
function getReviewerName($pdo, $student_id) {
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = :id");
    $stmt->execute(['id' => $student_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//Creates a pdf using fpdf library
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16); 
$pdf->Cell(0, 10, 'Peer Review Report for ' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']), 0, 1, 'C');
$pdf->Ln(10);

// Sees if there are reviews for the student if so report is generated
if (empty($reviews)) {
    $pdf->Cell(0, 10, "No reviews found for this student.", 0, 1);
} else {
// average of scores  
	$average_ratings = [];
    
    foreach ($reviews as $review) {
        $average_rating = ($review['q1_rating'] + $review['q2_rating'] + $review['q3_rating'] + $review['q4_rating'] + $review['q5_rating']) / 5;
        $average_ratings[] = $average_rating;
    }
    
    // Calculate the overall average rating
    $overall_average = array_sum($average_ratings) / count($average_ratings);

    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Overall Average Rating: ' . number_format($overall_average, 2), 0, 1, 'C');
    $pdf->Ln(5); 

    // Scores Table
    $pdf->SetFont('Arial', 'B', 12); 
    $pdf->Cell(50, 10, 'Reviewer', 1);
    $pdf->Cell(15, 10, 'Rev ID', 1);
    $pdf->Cell(25, 10, 'Avg Score', 1);
    $pdf->Cell(20, 10, 'Q1 Score', 1);
    $pdf->Cell(20, 10, 'Q2 Score', 1);
    $pdf->Cell(20, 10, 'Q3 Score', 1);
    $pdf->Cell(20, 10, 'Q4 Score', 1);
    $pdf->Cell(20, 10, 'Q5 Score', 1);
    $pdf->Ln();

    
    $pdf->SetFont('Arial', '', 11); 
	
    foreach ($reviews as $review) {
		//name for reviewer
        $reviewer = getReviewerName($pdo, $review['student_id']);
        $reviewer_name = htmlspecialchars($reviewer['first_name'] . ' ' . $reviewer['last_name']);

        // average for review
        $average_rating = ($review['q1_rating'] + $review['q2_rating'] + $review['q3_rating'] + $review['q4_rating'] + $review['q5_rating']) / 5;

       
        $pdf->Cell(50, 10, $reviewer_name, 1);
        $pdf->Cell(15, 10, $review['id'], 1);
        $pdf->Cell(25, 10, number_format($average_rating, 2), 1);
        $pdf->Cell(20, 10, $review['q1_rating'], 1);
        $pdf->Cell(20, 10, $review['q2_rating'], 1);
        $pdf->Cell(20, 10, $review['q3_rating'], 1);
        $pdf->Cell(20, 10, $review['q4_rating'], 1);
        $pdf->Cell(20, 10, $review['q5_rating'], 1);
        $pdf->Ln();
    }

    $pdf->Ln(10); 
    $pdf->SetFont('Arial', 'B', 12); 
    $pdf->Cell(0, 10, 'Comments from Reviews', 0, 1);
    $pdf->Ln(5); 

    // Comments section
    $pdf->SetFont('Arial', '', 11); 
    foreach ($reviews as $review) {
        // name for reviewer
        $reviewer = getReviewerName($pdo, $review['student_id']);
        $reviewer_name = htmlspecialchars($reviewer['first_name'] . ' ' . $reviewer['last_name']);

$pdf->SetFont('Arial', 'B', 11); 
$pdf->Cell(30, 10, 'Reviewer: ', 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $reviewer_name, 0);
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 11); 
$pdf->Cell(30, 10, 'Review ID: ', 0);
$pdf->SetFont('Arial', '', 11); 
$pdf->Cell(0, 10, $review['id'], 0);
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 11); 
$pdf->MultiCell(0, 10, 'Team member participated in team meetings/online discussions: ', 0);
$pdf->SetFont('Arial', '', 11); 
$pdf->MultiCell(0, 10, $review['q1_comment'], 0);

$pdf->SetFont('Arial', 'B', 11); 
$pdf->MultiCell(0, 10, 'Team member assignments were handed in, in a timely manner: ', 0);
$pdf->SetFont('Arial', '', 11); 
$pdf->MultiCell(0, 10, $review['q2_comment'], 0);

$pdf->SetFont('Arial', 'B', 11); 
$pdf->MultiCell(0, 10, 'Team member produced quality work: ', 0);
$pdf->SetFont('Arial', '', 11); 
$pdf->MultiCell(0, 10, $review['q3_comment'], 0);

$pdf->SetFont('Arial', 'B', 11); 
$pdf->MultiCell(0, 10, 'Group interaction was professional and respectful: ', 0);
$pdf->SetFont('Arial', '', 11); 
$pdf->MultiCell(0, 10, $review['q4_comment'], 0);

$pdf->SetFont('Arial', 'B', 11); 
$pdf->MultiCell(0, 10, 'Team member willingly engaged: ', 0);
$pdf->SetFont('Arial', '', 11); 
$pdf->MultiCell(0, 10, $review['q5_comment'], 0);

$pdf->Ln();

    }
}

$pdf->Output();
?>
