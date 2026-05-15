<?php 
    include 'knapsack.php';
    include 'validate_session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="resources/bootstrap.min.css">
    <link rel="stylesheet" href="resources/dataTables.dataTables.css">
    <link rel="stylesheet" href="resources/dashboard.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold fs-3"> Knapsack Dashboard </span>

            <div class="d-flex align-items-center gap-3">
                <span class="text-black">Welcome, <strong><?= $_SESSION['username'] ?></strong></span>
                <button class="btn btn-light btn-sm" onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row g-4">

            <!-- ITEM INFO -->
            <div class="col-lg-4">
                <div class="card shadow border-0 dashboard-card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Search</h3>
                        <form action="knapsack.php" method="post">
                            <div class="mb-3">
                                <label class="form-label">Items</label>
                                <input type="text" class="form-control" id="items" name="items" placeholder="notebook, pencil, paper, etc." required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Budget</label>
                                <input type="number" class="form-control" id="budget" name="budget" placeholder="50.00" required>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="singleProduct" name="singleProduct" value="ENABLED">
                                <label class="form-check-label">One item per product type</label>
                            </div>
                            <button type="submit" name="run" class="btn btn-primary w-100">Run Algorithm</button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- OPTIMAL ITEMS -->
            <div class="col-lg-8">
                <div class="card shadow border-0 dashboard-card">
                    <div class="card-body">
                        <h3 class="mb-3">Selected (Optimal)</h3>
                        <div class="selected-scroll">
                            <?php if (empty($_SESSION['selectedItems'])): ?>
                                <div class="alert alert-warning">
                                    No valid combination found.<br>
                                    Check if the product names are spelled correctly.
                                </div>
                            <?php else: ?>
                                <?php foreach ($_SESSION['selectedItems'] as $item): ?>
                                    <div class="product-card">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input">
                                            <label class="form-check-label">Checked</label>
                                        </div>
                                        <h5><?= $item['product_name'] ?></h5>
                                        <p><strong>Type: </strong><?= $item['product_type'] ?></p>
                                        <p><strong>Brand: </strong><?= $item['brand_name'] ?></p>
                                        <p><strong>Price: </strong>₱<?= number_format($item['product_price'], 2) ?></p>
                                        <p><strong>Score: </strong><?= number_format($item['product_score'], 2) ?></p>
                                        <p><strong>Platform: </strong><?= $item['platform_name'] ?></p>
                                        <a href="platform_redirect.php" class="btn btn-outline-primary btn-sm"> Visit Site </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'scripts.php' ?>
</body>
</html>