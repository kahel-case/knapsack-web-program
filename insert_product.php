<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_name = $_POST["product_name"];
    $product_type = $_POST["product_type"];
    $product_price = $_POST["product_price"];
    $product_star_rating = $_POST["product_star_rating"];
    $product_reviews = $_POST["product_reviews"];
    $product_sold = $_POST["product_sold"];
    $product_platform = $_POST["product_platform"];
    $product_brand = $_POST["product_brand"];
    $product_stock = $_POST["product_stock"];

    $sql = "SELECT 
                p.platform_id,
                b.brand_id,
                pt.product_type_id
            FROM platforms p
            JOIN brands b ON b.brand_name = ?
            JOIN product_types pt ON pt.product_type = ?
            WHERE p.platform_name = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $product_brand, $product_type, $product_platform);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $platform_id = $row["platform_id"];
        $product_type_id = $row["product_type_id"];
        $brand_id = $row["brand_id"];

        $sql = "INSERT INTO products(platform_id, product_type_id, brand_id, product_name, product_price, product_star_rating, product_reviews, product_sold, product_stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiisddiii",$platform_id,$product_type_id,$brand_id,$product_name,$product_price,$product_star_rating,$product_reviews,$product_sold,$product_stock);

        if($stmt->execute()){
            echo "<script>alert('Product Successfully Inserted!'); window.location.href='admin_dashboard.php';</script>";
        }else{
            echo "<script>alert('Error occurred while inserting.'); window.location.href='admin_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Error.'); window.location.href='admin_dashboard.php';</script>";
    }

    $stmt->close();    
}