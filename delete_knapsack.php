<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $knapsack_id = $_POST['delete_knapsack'] ?? [];

    $stmt = $conn->prepare(
        "DELETE FROM knapsacks WHERE user_id = ? AND knapsack_id = ?"
    );

    $stmt->bind_param("ii", $user_id, $knapsack_id);
    $stmt->execute();
                      
    $_SESSION['msg'] = "Successfully deleted knapsack!" ;
    header("Location: dashboard_include.php");

    $stmt->close();
    
}