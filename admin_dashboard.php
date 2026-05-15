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

    <link rel="stylesheet" href="resources/bootstrap.min.css">
    <link rel="stylesheet" href="resources/admin.css">
    <link rel="stylesheet" href="resources/admin-dashboard.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold fs-3">Knapsack Admin</span>

            <div class="d-flex align-items-center gap-3">
                <span class="text-white">Admin: <strong><?= $_SESSION['username'] ?></strong></span>
                <button class="btn btn-light btn-sm" onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="admin-card mx-auto">
            <h2 class="mb-4 text-center">Insert New Product</h2>
            <form action="insert_product.php" method="post">
                <div class="row">

                    <!-- Left column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="product_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product Type</label>
                            <input type="text" name="product_type"class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Platform</label>
                            <input type="text" name="product_platform" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <input type="text" name="product_brand" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="product_price" class="form-control" required>
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Star Rating</label>
                            <input type="number" step="0.1" name="product_star_rating" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reviews</label>
                            <input type="number" name="product_reviews" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount Sold</label>
                            <input type="number" name="product_sold" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="product_stock" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Insert Product</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>