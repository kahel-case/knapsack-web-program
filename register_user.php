<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    $sql = "SELECT username FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $stmt->store_result();

    if($password == $confirm_password){
        if(!$stmt->num_rows>0){

            $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss",$username,$hashedPassword);

            if($stmt->execute()){
                echo "<script>alert('Successfully Registered!'); window.location.href='index.php';</script>";
            }else{
                echo "<script>alert('Error occurred while registering.'); window.location.href='register.php';</script>";
            }

        }else{
            echo "<script>alert('Username already exists!'); window.location.href='register.php';</script>";
        }
    }else{
        echo "<script>alert('Passwords do not match!'); window.location.href='register.php';</script>";
    }

    $stmt->close();
    
}