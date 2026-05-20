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
    <?php include 'header.php'; ?>

    <div class="container py-4">
        <div class="card shadow border-0 dashboard-card">

            <div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Knapsack List</h3>
        </div>

        <div class="accordion selected-scroll-1" id="knapsackAccordion">

            <?php if (empty($knapsacks)): ?>
                <div class="alert alert-warning mb-0">
                    No knapsacks available.
                </div>
            <?php else: ?>

                <?php foreach ($knapsacks as $id => $knapsack): ?>
                    <div class="accordion-item mb-2 border rounded">

                        <h2 class="accordion-header d-flex align-items-center justify-content-between px-3 py-2">

                            <button class="accordion-button collapsed bg-light shadow-none"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#knapsack-<?= $knapsack['knapsack_id'] ?>">
                                <?= htmlspecialchars($knapsack['name']) ?>
                            </button>

                            <form action="delete_knapsack.php" method="post" class="ms-2">
                                <input type="hidden" name="delete_knapsack" value="<?= $knapsack['knapsack_id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                                <button class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>
                            </form>

                        </h2>

                        <div id="knapsack-<?= $knapsack['knapsack_id'] ?>" class="accordion-collapse collapse">
                            <div class="accordion-body">

                                <?php if (!empty($knapsack['items'])): ?>
                                    <div class="row g-3">

                                        <?php foreach ($knapsack['items'] as $item): ?>
                                            <div class="col-md-6">

                                                <div class="card h-100 shadow-sm border-0">
                                                    <div class="card-body">

                                                        <h5 class="card-title">
                                                            <a href="platform_redirect.php">
                                                                <?= htmlspecialchars($item['product_name']) ?>
                                                            </a>
                                                        </h5>

                                                        <p class="mb-1"><strong>Type:</strong> <?= $item['product_type'] ?></p>
                                                        <p class="mb-1"><strong>Brand:</strong> <?= $item['brand_name'] ?></p>
                                                        <p class="mb-1"><strong>Price:</strong> ₱<?= number_format($item['product_price'], 2) ?></p>
                                                        <p class="mb-2"><strong>Score:</strong> <?= number_format($item['product_score'], 0) ?></p>

                                                        <?php
                                                            $rating = $item['product_star_rating'];
                                                            $badge = $rating > 4 ? "success" : ($rating > 3 ? "warning" : "danger");
                                                        ?>

                                                        <div class="mb-2">
                                                            <span class="badge bg-<?= $badge ?>">
                                                                <?= $rating ?>★
                                                            </span>
                                                            <span class="badge bg-secondary">
                                                                <?= $item['product_reviews'] ?> reviews
                                                            </span>
                                                        </div>

                                                        <p class="mb-3"><strong>Platform:</strong> <?= $item['platform_name'] ?></p>

                                                        <form action="remove_item.php" method="post">
                                                            <input type="hidden" name="inclusion_id" value="<?= $item['inclusion_id'] ?>">
                                                            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                                                            <button class="btn btn-sm btn-outline-danger">
                                                                Remove Item
                                                            </button>
                                                        </form>

                                                    </div>
                                                </div>

                                            </div>
                                        <?php endforeach; ?>

                                    </div>
                                <?php else: ?>
                                    <div class="text-muted">No items in this knapsack.</div>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

        </div>

        <div class="mt-3">
            <button type="button"
                    onclick="window.location.href='back.php'"
                    class="btn btn-success rounded-pill px-4">
                Back
            </button>
        </div>

    </div>
</div>

        </div>                            
    </div>


    <script src="date.js"></script>
    <?php include 'scripts.php' ?>

</body>
</html>