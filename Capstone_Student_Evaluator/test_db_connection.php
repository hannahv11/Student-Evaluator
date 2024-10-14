<?php
require_once 'db_connection.php';

try {
    echo "Connection Successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>