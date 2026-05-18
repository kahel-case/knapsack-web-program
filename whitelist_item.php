<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $whitelist_item = $_POST['whitelist_item'] ?? [];

    $stmt = $conn->prepare(
        "DELETE FROM excluded_items WHERE user_id = ? AND exclusion_id = ?"
    );

    $stmt->bind_param("ii", $user_id, $whitelist_item);
    $stmt->execute();
                      
    $_SESSION['msg'] = "Successfully whitelisted item!" ;
    header("Location: dashboard_exclude.php");

    $stmt->close();
    
}