<?php 
    include 'knapsack.php';
    include 'validate_session.php';
    include 'db_connection.php';

    $items = $_SESSION['items'] ?? '';
    $budget = $_SESSION['budget'] ?? '';
    $singleProduct = $_SESSION['singleProduct'] ?? '';

    $sql = "SELECT * FROM knapsacks WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $knapsacks = [];
    while ($row = $result->fetch_assoc()) {
        $knapsacks[] = $row;
    }

    if (isset($_SESSION['msg'])) {
        phpAlert($_SESSION['msg']);
        unset($_SESSION['msg']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="resources/bootstrap.min.css">
    <link rel="stylesheet" href="resources/dataTables.dataTables.css">
    <link rel="stylesheet" href="resources/dashboard.css?v=6">

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
                        <div class="mb-3">
                            <label for="select_knapsack" class="form-label fw-semibold">
                                Select Knapsack
                            </label>

                            <form method="post" id="item_form">
                                    <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['user_id'] ?>" required>
                            <div class="input-group">
                                <select id="select_knapsack" name="select_knapsack" class="form-select">
                                    <?php if (empty($knapsacks)): ?>
                                        <option value="">No knapsacks available</option>
                                    <?php else: ?>
                                        <?php foreach ($knapsacks as $knapsack): ?>
                                            <option value="<?= $knapsack['knapsack_id'] ?>">
                                                <?= htmlspecialchars($knapsack['knapsack_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>

                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#createKnapsack"> + Create</button>
                            </div>
                            <div class="form-text">Choose an existing knapsack or create a new one.</div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" onclick="window.location.href='dashboard_include.php'" class="btn btn-success flex-fill rounded-pill">View Knapsacks</button>
                            <button type="button" onclick="window.location.href='dashboard_exclude.php'" class="btn btn-danger flex-fill rounded-pill">View Excluded Items</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- OPTIMAL ITEMS -->
            <div class="col-lg-8 flex-column">

                <div class="card shadow border-0 dashboard-card">
                    <div class="card-body">
                        <?php if (empty($_SESSION['totalPrice'])): ?> <h3 class="mb-3">Selected (Optimal)</h3>
                        <?php else: ?>
                            <div>
                                <input type="hidden" id="total_price" name="total_price" value="<?= number_format($_SESSION['totalPrice'], 2) ?>" required>
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
                        
                        <div class="selected-scroll-1">
                            <?php if (empty($_SESSION['selectedItems'])): ?>
                            <div class="alert alert-warning">
                                No valid combination found.<br>
                                Check if the product names are spelled correctly.
                            </div>
                            <?php else: ?>
                                <?php foreach ($_SESSION['selectedItems'] as $item): ?>
                                    <div class="product-card">
                                        <input type="hidden" id="save_<?= $item['product_id'] ?>" name="saved_items[]" value="<?= $item['product_id'] ?>" required>
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
                                            <label for="exclude_<?= $item['product_id'] ?>" class="button-label-exclude btn btn-outline-danger btn-sm">Exclude Item</label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                </form>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($_SESSION['selectedItems'])): ?>
                        <button type="submit" formaction="exclude_items.php" form="item_form" class="btn btn-danger mt-3 mx-1 w-25">Apply Exclusions</button>
                        <button type="submit" formaction="include_items.php" form="item_form" class="btn btn-success mt-3 mx-1 w-25">Save Knapsack</button>
                        <?php endif; ?>
                    </div>


                </div>
            </div>
            
        </div>  
    </div>

                            <div class="modal fade" id="createKnapsack" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Create Knapsack</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="insert_knapsack.php" method="post">
                                            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>" required>
                                            <input type="hidden" id="startDate" name="startDate">
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Knapsack Name</label>
                                                    <input type="text" class="form-control rounded-3" name="knapsack_name" required>
                                                </div>
                                            </div>

                                            <div class="modal-footer border-0 px-4 pb-4">
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

    <script src="date.js"></script>
    <?php include 'scripts.php' ?>

</body>
</html>