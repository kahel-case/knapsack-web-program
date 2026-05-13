<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "knapsack";

$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("Connection failed: ". $conn->connect_error);
}