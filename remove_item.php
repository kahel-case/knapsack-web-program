<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $inclusion_id = $_POST['inclusion_id'] ?? [];

    $stmt = $conn->prepare(
        "DELETE FROM included_items WHERE user_id = ? AND inclusion_id = ?"
    );

    $stmt->bind_param("ii", $user_id, $inclusion_id);
    $stmt->execute();
                      
    $_SESSION['msg'] = "Successfully removed item!" ;
    header("Location: dashboard_include.php");

    $stmt->close();
    
}