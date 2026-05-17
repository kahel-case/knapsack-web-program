<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['user_id'];
            
            if ($row['role'] == 'ADMIN') {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }

        } else {
            echo "<script>alert('Invalid password!'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('User does not exist!'); window.location.href='index.php';</script>";
    }
}
$conn->close();
