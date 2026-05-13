<?php
session_start();

include 'db_connection.php';

    $username = "admin";
    $password = "admin123";
    $role = "ADMIN";



        

            $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss",$username,$hashedPassword,$role);

            if($stmt->execute()){
                echo "<script>alert('Successfully Registered!'); window.location.href='index.php';</script>";
            }else{
                echo "<script>alert('Error occurred while registering.'); window.location.href='register.php';</script>";
            }

        
    

    $stmt->close();
    
