<?php 
    include 'knapsack.php';
    include 'validate_session.php';
    include 'db_connection.php';

    $items = $_SESSION['items'] ?? '';
    $budget = $_SESSION['budget'] ?? '';
    $singleProduct = $_SESSION['singleProduct'] ?? '';

    $sql = "SELECT k.knapsack_id, k.knapsack_name, i.inclusion_id, p.*, b.brand_name, pl.platform_name, pt.product_type
            FROM knapsacks k
            LEFT JOIN included_items i 
                ON k.knapsack_id = i.knapsack_id
            LEFT JOIN products p
                ON i.product_id = p.product_id
            LEFT JOIN platforms pl
                ON p.platform_id = pl.platform_id
            LEFT JOIN brands b
                ON p.brand_id = b.brand_id
            LEFT JOIN product_types pt
                ON p.product_type_id = pt.product_type_id
            WHERE k.user_id = ?
            ORDER BY k.knapsack_id;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $knapsacks = [];
    while ($row = $result->fetch_assoc()) {

        $kid = $row['knapsack_id'];

        // Create knapsack entry if not existing
        if (!isset($knapsacks[$kid])) {
            $knapsacks[$kid] = [
                'name' => $row['knapsack_name'],
                'knapsack_id' => $row['knapsack_id'],
                'items' => []
            ];
        }

        // Add item if item exists
        if ($row['inclusion_id']) {
            $knapsacks[$kid]['items'][] = [
                'inclusion_id' => $row['inclusion_id'],
                'product_name' => $row['product_name'],
                'product_type' => $row['product_type'],
                'brand_name' => $row['brand_name'],
                'product_price' => $row['product_price'],
                'product_score' => $row['product_score'],
                'product_star_rating' => $row['product_star_rating'],
                'product_reviews' => $row['product_reviews'],
                'platform_name' => $row['platform_name']
            ];
        }
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
    <link rel="stylesheet" href="resources/dashboard.css?v=7">

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
        <div class="card shadow border-0 dashboard-card">

            <div class="card-body">
                <h3 class="mb-3">Knapsack List</h3>
                <div class="selected-scroll-1">
                    <?php if (empty($knapsacks)): ?>
                    <div class="alert alert-warning">
                        There are currently no available knapsacks.<br>
                    </div>
                    <?php else: ?>
                        <?php foreach ($knapsacks as $id => $knapsack): ?>
                            <div class="knapsack">
                                <!-- Trigger Element -->
                                <button type="button" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#knapsack-<?= $knapsack['knapsack_id'] ?>"><?= htmlspecialchars($knapsack['name']) ?></button>
                                <form action="delete_knapsack.php" method="post">
                                    <input type="hidden" id="delete_knapsack" name="delete_knapsack" value="<?= $knapsack['knapsack_id'] ?>" required>
                                    <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['user_id'] ?>" required>
                                    <button type="submit" class="btn btn-danger">Delete Knapsack</button>
                                </form>                                
                                <div class="collapse" id="knapsack-<?= $knapsack['knapsack_id'] ?>">
                                    <div class="card card-body mt-2">
                                        <?php if (!empty($knapsack['items'])): ?>
                                            <?php foreach ($knapsack['items'] as $item): ?>
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
                                                    <form action="remove_item.php" method="post">
                                                        <input type="hidden" id="inclusion_id" name="inclusion_id" value="<?= $item['inclusion_id'] ?>" required>
                                                        <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['user_id'] ?>" required>
                                                        <button type="submit" class="btn btn-danger">Remove Item</button>
                                                    </form>  
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p>No items.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>

                <button type="button" onclick="window.location.href='back.php'" class="btn btn-success flex-fill rounded-pill px-xl-4  ">Back</button>
            </div>

        </div>                            
    </div>


    <script src="date.js"></script>
    <?php include 'scripts.php' ?>

</body>
</html>