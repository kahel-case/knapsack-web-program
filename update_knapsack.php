<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $knapsack_name = $_POST["knapsack_name"];
    $knapsack_id = $_POST["knapsack_id"];

    $sql = "UPDATE knapsacks SET knapsack_name = ? WHERE user_id = ? AND knapsack_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii",$knapsack_name,$user_id,$knapsack_id);

    if($stmt->execute()){
        $_SESSION['msg'] = "Successfully updated knapsack!" ;
        header("Location: dashboard.php");
    }else{
        $_SESSION['msg'] = "Error when updating knapsack!" ;
        header("Location: dashboard.php");
    }

    $stmt->close();
}