<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $excluded = $_POST['excluded_items'] ?? [];

    $stmt = $conn->prepare(
        "INSERT IGNORE INTO excluded_items (user_id, product_id) VALUES (?, ?)"
    );

    foreach ($excluded as $product_id) {
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
                            
    $_SESSION['msg'] = "Successfully excluded items from selection!" ;
    header("Location: dashboard.php?openModal=1");

    $stmt->close();
    
}