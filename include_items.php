<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST['select_knapsack'])) {
        $_SESSION['msg'] = "There are no selected knapsacks!" ;
        header("Location: dashboard.php");
        exit();
    }

    $user_id = $_POST["user_id"];
    $saved = $_POST['saved_items'] ?? [];
    $knapsack_id = $_POST['select_knapsack'] ?? '';
    $knapsack_total_price = $_POST['total_price'] ?? 0;


    $stmt = $conn->prepare(
        "INSERT IGNORE INTO included_items (user_id, product_id, knapsack_id) VALUES (?, ?, ?)"
    );

    foreach ($saved as $product_id) {
        $stmt->bind_param("iii", $user_id, $product_id, $knapsack_id);
        $stmt->execute();
    }

    $_SESSION['msg'] = "Successfully added items to knapsack!" ;
    header("Location: dashboard.php?openModal=1");

    $stmt->close();
    
}