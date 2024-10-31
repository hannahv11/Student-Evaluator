<?php
$host = 'localhost'; //connects to localhost
$db = 'peer_review_db'; //created db
$user = 'root'; //default db user for testing purposes
$pass = ''; //default pass
$charset = 'utf8mb4'; //character set

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    //throws visible exceptions in case of errors
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

try {
    //creates PDO instance to interact with DB
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    //catches PDO related exceptions just in case
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>