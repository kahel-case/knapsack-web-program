<?php 
    include 'knapsack.php';
    include 'validate_session.php';
    include 'db_connection.php';

    $items = $_SESSION['items'] ?? '';
    $budget = $_SESSION['budget'] ?? '';
    $singleProduct = $_SESSION['singleProduct'] ?? '';

    $sql = "SELECT DISTINCT p.*, b.brand_name, pt.product_type, pl.platform_name, ei.exclusion_id, ei.user_id FROM products p
            JOIN excluded_items ei ON p.product_id = ei.product_id
            JOIN brands b ON p.brand_id = b.brand_id
            JOIN product_types pt ON p.product_type_id = pt.product_type_id
            JOIN platforms pl ON p.platform_id = pl.platform_id
            WHERE ei.user_id = ?;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $excluded_items = [];
    while ($row = $result->fetch_assoc()) {
        $excluded_items[] = $row;
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
    <?php include 'header.php'; ?>

    <div class="container py-4">
        <div class="card shadow border-0 dashboard-card">

            <div class="card-body">
                <h3 class="mb-3">Excluded Items</h3>
                <div class="selected-scroll-1">
                    <?php if (empty($excluded_items)): ?>
                    <div class="alert alert-warning">
                        There are currently no excluded items.<br>
                    </div>
                    <?php else: ?>
                        <?php foreach ($excluded_items as $item): ?>
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
                                <form action="whitelist_item.php" method="post">
                                    <input type="hidden" id="whitelist_item" name="whitelist_item" value="<?= $item['exclusion_id'] ?>" required>
                                    <input type="hidden" id="user_id" name="user_id" value="<?= $item['user_id'] ?>" required>
                                    <button type="submit" class="btn btn-outline-success">Whitelist</button>
                                </form>
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