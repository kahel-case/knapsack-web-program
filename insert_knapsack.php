<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $startDate = $_POST["startDate"];
    $knapsack_name = $_POST["knapsack_name"];

    $sql = "INSERT INTO knapsacks (user_id, knapsack_name, date_created) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss",$user_id,$knapsack_name,$startDate);

    if($stmt->execute()){
        $_SESSION['msg'] = "Successfully created knapsack!" ;
        header("Location: dashboard.php");
    }else{
        $_SESSION['msg'] = "Error when created knapsack!" ;
        header("Location: dashboard.php");
    }

    $stmt->close();
}