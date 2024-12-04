<?php
session_start();
include 'db_connection.php';
include 'active_user.php';

//Importing required libraries, now includes PHPSpreadsheet library files
require 'db_connection.php';
require 'vendor/autoload.php';
//using PHP spreadsheet library
use PhpOffice\PhpSpreadsheet\IOFactory;

//variable for checking if data was inserted (was the excel file formatted correctly?)
$data_inserted = false;

//Handles file upload, checks if its an excel file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    //Checks if file was uploaded via HTTP POST, necessary security check
    if (is_uploaded_file($file)) {
        //Accesses spreadsheet to read via PHPSpreadsheet
        //getActiveSheet prevents multiple sheets from being read if
        //more than one is uploaded
        try {
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            //Starts on second row of excel file as to skip the header text from downloadable excel spreadsheet
            $rowIterator = $worksheet->getRowIterator(2);
            //loops through each row
            foreach ($rowIterator as $rowIndex => $row) {
                //reads values from each row
                $cells = $row->getCellIterator();
                $cells->setIterateOnlyExistingCells(false); //ensures all cells are read

                //reads values from column A-D (student_id, student_name, team_id, team_name)
                $student_id = $cells->current()->getValue(); //student_id
                $cells->next();
                $student_name = $cells->current()->getValue(); //student_name
                $cells->next();
                $team_id = $cells->current()->getValue(); //team_id
                $cells->next();
                $team_name = $cells->current()->getValue(); //team_name

                //Checks if required values are missing
                if (empty($student_id) || empty($student_name) || empty($team_id) || empty($team_name)) {
                    continue; //skips rows with missing data
                }

                //Prevents duplicate entries if already in database. Doesn't repeat insert
                $stmt = $pdo->prepare("INSERT IGNORE INTO teams (student_id, team_id, team_name, student_name) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$student_id, $team_id, $team_name, $student_name])) {
                    $data_inserted = true;
                }
            }
            //displays respective message if excel file was formatted correctly in upload
            if ($data_inserted) {
                echo "Teams list successfully updated in Database.";
            } else {
                echo "Data uploaded invalid. Nothing was updated";
            }
        } catch (Exception $e) { //catches any errors
            echo "Error: " . $e->getMessage();
        }
    } 
    else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 10/15/2024  

    Filename: upload_teams.php
   -->
    <title>Upload Teams through spreadsheet</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel = "stylesheet" href="navigation.css">
            <link rel = "stylesheet" href="upload.css">
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
    <h1>Upload Team Assignments</h1>
        <form action="upload_teams.php" method="post" enctype="multipart/form-data">
            <label for="excel_file">Select Excel File:</label>
            <input type="file" name="excel_file" id="excel_file" required>
            <button type="submit">Upload and Import</button>
        </form>
</body>
</html>
