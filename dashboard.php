<?php 
    include 'knapsack.php';
    include 'validate_session.php';
    include 'db_connection.php';

    $items = $_SESSION['items'] ?? '';
    $budget = $_SESSION['budget'] ?? '';
    $singleProduct = isset($_SESSION['singleProduct']) && $_SESSION['singleProduct'];
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

    <style>
        /* Hide the actual checkbox */
.hidden-checkbox {
  display: none;
}

/* Style the label to look like a button */
.button-label {
  display: inline-block;
  background-color: transparent;
  border: 2px solid #fc4040;
  cursor: pointer;
  border-radius: 5px;
  user-select: none; /* Prevents text selection on rapid clicks */
}

/* Change appearance when the hidden checkbox is checked */
.hidden-checkbox:checked + .button-label {
  background-color: #fc4040;
  color: white;
  border-color: #fc4040;
}

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold fs-3">Knapsack Dashboard</span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white">Welcome, <strong><?= $_SESSION['username'] ?></strong></span>
                <button class="btn btn-danger btn-sm rounded-pill" onclick="window.location.href='logout.php'"><strong>Logout</strong></button>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row g-4">

            <!-- ITEM INFO -->
            <div class="col-lg-4 flex-column">
                <div class="card shadow border-0 dashboard-card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Search</h3>
                        <form action="knapsack.php" method="post">
                            <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['user_id'] ?>" required>
                            <div class="mb-3">
                                <label class="form-label">Items</label>
                                <input type="text" class="form-control" id="items" name="items" placeholder="notebook, pencil, paper, etc." value="<?= $items ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Budget (₱)</label>
                                <input type="number" class="form-control" id="budget" name="budget" placeholder="50.00" value="<?= $budget ?>" required>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="singleProduct" name="singleProduct" value="ENABLED" <?= $singleProduct ? 'checked' : '' ?>>
                                <label class="form-check-label">One item per product type</label>
                            </div>
                            <button type="submit" name="run" class="btn btn-warning w-100 rounded-pill px-4 text-white my-2" id="run_algorithm"><strong>Run Algorithm</strong></button>
                            <button type="button" onclick="window.location.href='unset_session.php'" class="btn btn-outline-danger w-100 rounded-pill px-4 text-warning my-2"><strong>Reset</strong></button>
                        </form>
                    </div>
                </div>

                <div class="card shadow border-0 dashboard-card mt-4">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Search</h3>
                        
                    </div>
                </div>
            </div>

            

            <!-- OPTIMAL ITEMS -->
            <div class="col-lg-8">
                <div class="card shadow border-0 dashboard-card">
                    <div class="card-body">
                        <?php if (empty($_SESSION['totalPrice'])): ?> <h3 class="mb-3">Selected (Optimal)</h3>
                        <?php else: ?>
                            <div>
                                <h3 class="mb-3">Selected (Optimal) - Total: <div class="badge bg-warning text-black">₱<?= number_format($_SESSION['totalPrice'], 2) ?></div></h3>
                                <div class="mb-3">
                                    Available Types: 
                                    <?php foreach ($_SESSION['selectedTypes'] as $item): ?>
                                        <div class="badge bg-primary">
                                            <?= $item['product_type'] ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="selected-scroll">
                            <?php if (empty($_SESSION['selectedItems'])): ?>
                            <div class="alert alert-warning">
                                No valid combination found.<br>
                                Check if the product names are spelled correctly.
                            </div>
                            <?php else: ?>
                                <form action="exclude_items.php" method="post" id="exclude_items">
                                    <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['user_id'] ?>" required>
                                <?php foreach ($_SESSION['selectedItems'] as $item): ?>
                                    <div class="product-card">
                                        <h5><a href="platform_redirect.php" class=""><?= $item['product_name'] ?></a></h5>
                                        <p><strong>Type: </strong><?= $item['product_type'] ?></p>
                                        <p><strong>Brand: </strong><?= $item['brand_name'] ?></p>
                                        <p><strong>Price: </strong>₱<?= number_format($item['product_price'], 2) ?></p>
                                        <p><strong>Score: </strong><?= number_format($item['product_score'], 0) ?>
                                            <?php if ($item['product_star_rating'] > 4): ?>
                                                <div class="badge bg-success text-white"><?= $item['product_star_rating'] ?>★</div>
                                                <div class="badge bg-success text-white"><?= $item['product_reviews'] ?></div>
                                            <?php elseif ($item['product_star_rating'] > 3): ?>
                                                <div class="badge bg-warning text-white"><?= $item['product_star_rating'] ?>★</div>
                                                <div class="badge bg-warning text-white"><?= $item['product_reviews'] ?></div>
                                            <?php else: ?>
                                                <div class="badge bg-danger text-white"><?= $item['product_star_rating'] ?>★</div>
                                                <div class="badge bg-danger text-white"><?= $item['product_reviews'] ?></div>
                                            <?php endif; ?>
                                        </p>
                                        <p><strong>Platform: </strong><?= $item['platform_name'] ?></p>
                                        <div class="button-checkbox">
                                            <input type="checkbox" id="exclude_<?= $item['product_id'] ?>" name="excluded_items[]" class="hidden-checkbox" value="<?= $item['product_id'] ?>">
                                            <label for="exclude_<?= $item['product_id'] ?>" class="button-label btn btn-outline-danger btn-sm">Exclude Item</label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                </form>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($_SESSION['selectedItems'])): ?>
                        <button type="submit" form="exclude_items" class="btn btn-danger mt-3">Apply Exclusions</button>
                        <?php endif; ?>
                    </div>


                </div>
            </div>
            
        </div>  
    </div>

    <script src="date.js"></script>
    <?php include 'scripts.php' ?>

</body>
</html>