<?php
include 'db_connection.php';

if (isset($_SESSION['id'])) { 
    //uses global PHP SESSION variable to retrieve user information from logged-in ID
    $user_id = $_SESSION['id'];
    $stmt = $pdo->prepare("SELECT  first_name, last_name 
                            FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $active_user = $stmt->fetch(PDO::FETCH_ASSOC);

    //stores user session variables for use across application
    $_SESSION['first_name'] = $active_user['first_name']; 
    $_SESSION['last_name'] = $active_user['last_name'];
}

?>
<!-- Uses global session ID from logged in user to display status -->
<!DOCTYPE html>
<html lang="en">
    <!--
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 10/15/2024  

    Filename: active_user.php
   -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel = "stylesheet" href="active_user.css">
        <div id="login_status">
            <?php 
                if (isset($_SESSION['id'])) {
                    echo "You are currently logged in as: " . $_SESSION['first_name'] . " " . $_SESSION['last_name'];
                }
                else {
                    echo "You are currently not logged in.";
                }       
            ?>
        </div>
</html>
