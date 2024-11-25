<?php
session_start();
include 'db_connection.php';

//checks if current login is student role
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    // Sends you back to login if not logged in as student or logged in as faculty
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['id'];
$team_id = $_POST['team_id'] ?? '';

//checks for existing team_id values in teams table (should be created by faculty via teams excel upload page)
if (empty($team_id)) {
    header("Location: student.php?error=team_not_found"); //returns error if applicable
    exit;
}

try {
    //checks if current login is already part of a group
    $checkTeamQuery = "SELECT team_id FROM teams WHERE student_id = ?";
    $checkTeamStmt = $pdo->prepare($checkTeamQuery);
    $checkTeamStmt->execute([$student_id]);
    if ($checkTeamStmt->fetch()) { //returns error if applicable
        header("Location: student.php?error=already_in_team");
        exit;
    }

    //checks if team selected by user exists
    $teamExistsQuery = "SELECT team_id FROM teams WHERE team_id = ?";
    $teamExistsStmt = $pdo->prepare($teamExistsQuery);
    $teamExistsStmt->execute([$team_id]);
    if (!$teamExistsStmt->fetch()) {
        header("Location: student.php?error=team_not_found");
        exit;
    }

    //when student presses 'join team,' writes their info via SQL into teams table as a new entry into their respective team
    $joinTeamQuery = "INSERT INTO teams (team_id, team_name, student_id, student_name)
                      SELECT team_id, team_name, ?, ?
                      FROM teams WHERE team_id = ?
                      LIMIT 1";
    $joinTeamStmt = $pdo->prepare($joinTeamQuery);
    $success = $joinTeamStmt->execute([$student_id, $_SESSION['first_name'], $team_id]);

    if ($success && $joinTeamStmt->rowCount() > 0) { //shows a message if a team was joined or if there's an error.
        header("Location: student.php?success=team_joined");
    } else {
        header("Location: student.php?error=team_join_failed");
    }
    exit;

} catch (PDOException $e) { //error handling if necessary
    error_log("Error: " . $e->getMessage());
    header("Location: student.php?error=unexpected_error");
    exit;
}