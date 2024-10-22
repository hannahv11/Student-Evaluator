<?php
$host = 'localhost'; //Connects to LocalHost and created database
$dbname = 'peer_review_db';
$username = 'root'; //Default SQL user and pass for testing purposes (Will NOT be permanent)
$password = '';

$con = mysqli_connect("localhost", "root", "", "peer_review_db");

try {
    //Connects to database with PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    //Throws exception if there's a connection error from the DB
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    //Stops connection script and displays error in case of failure
    die("Connection Failed: " . $e->getMessage());
}
?>