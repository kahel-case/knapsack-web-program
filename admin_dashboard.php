<?php 
    session_start();
    include 'validate_session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <header>
        <h1>Knapsack Thingy: Admin</h1>
        <div>
            <h2>User: <?php echo $_SESSION['username']?></h2>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </header>

    <div>
        <form action="insert_product.php" method="post">
            <h1>Insert New Product</h1>
            <div>
                <label for="product_name">Product Name: </label>
                <input type="text" id="product_name" name="product_name" required>
            </div>
            <div>
                <label for="product_type">Product Type: </label>
                <input type="text" id="product_type" name="product_type" required>
            </div>
            <div>
                <label for="product_platform">Product Platform: </label>
                <input type="text" id="product_platform" name="product_platform" required>
            </div>
            <div>
                <label for="product_brand">Product Brand: </label>
                <input type="text" id="product_brand" name="product_brand" required>
            </div>
            <div>
                <label for="product_price">Product Price: </label>
                <input type="text" id="product_price" name="product_price" required>
            </div>
            <div>
                <label for="product_star_rating">Product Star Rating: </label>
                <input type="number" step="0.1" id="product_star_rating" name="product_star_rating" required>
            </div>
            <div>
                <label for="product_reviews">Product Number of Reviews: </label>
                <input type="number" id="product_reviews" name="product_reviews" required>
            </div>
            <div>
                <label for="product_sold">Product Amount Sold: </label>
                <input type="number" id="product_sold" name="product_sold" required>
            </div>
            <div>
                <label for="product_stock">Product Stock: </label>
                <input type="text" id="product_stock" name="product_stock" required>
            </div>
            <div>
                <button type="submit">Insert Product</button>
            </div>
        </form>
    </div>
</body>
</html>