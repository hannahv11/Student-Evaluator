<?php
session_start();
include 'db_connection.php';

//Check if the user is logged in and has the 'instructor' role
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit;
}

//retrieves student IDs and names from the database
$sql = "SELECT id, first_name FROM users WHERE role = 'student'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

//headers for excel sheet
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="student_ids.csv"');

//allows us to write to file
$output = fopen('php://output', 'w');

//outputs File headers as labels for the admin user in the Excel file
fputcsv($output, ['Student ID', 'First Name', 'Team ID', 'Team Name']);

//puts student data into file
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [$row['id'], $row['first_name']]);
}

// Close the file stream
fclose($output);

exit;
?>
