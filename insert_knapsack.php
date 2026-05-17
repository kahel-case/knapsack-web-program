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
        echo "<script>alert('Successfully created knapsack!'); window.location.href='dashboard.php';</script>";
    }else{
        echo "<script>alert('Error occurred while registering.'); window.location.href='dashboard.php';</script>";
    }

        

    $stmt->close();
    
}