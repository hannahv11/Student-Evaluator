<?php
include 'db_connection.php';


if (isset($_SESSION['id'])) {
    // checks user and stores name infor
    $user_id = $_SESSION['id'];
    $stmt = $pdo->prepare("SELECT  first_name, last_name FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $active_user = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['first_name'] = $active_user['first_name'];
    $_SESSION['last_name'] = $active_user['last_name'];
}


?>

<!DOCTYPE html>
<html lang="en">

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