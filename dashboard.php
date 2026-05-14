<?php 
    include 'knapsack.php';
    include 'validate_session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/bootstrap.min.css">
    <link rel="stylesheet" href="resources/dataTables.dataTables.css">
    
    <title>Dashboard</title>
</head>

<body>
    <header>
        <h1>Knapsack Thingy</h1>
        <div>
            <h2>User: <?php echo $_SESSION['username']?></h2>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </header>

    <div>
        <h2>Selected (Optimal)</h2>
        <?php if (empty($_SESSION['selectedItems'])): ?>
            <p>No valid combination found.</p>
            <p>Check if the product you wish to add is spelled correctly.</p>
        <?php else: ?>
            <?php foreach ($_SESSION['selectedItems'] as $item): ?>
                <div>
                    <div>
                        <input type="checkbox" id="checked" name="checked">
                        <label for="checked">Checked</label>
                    </div>
                    <div>
                        <h3><?= $item['product_name'] ?></h3>
                        <p>Type: <?= $item['product_type'] ?></p>
                        <p>Brand: <?= $item['brand_name'] ?></p>
                        <p>Price: ₱<?= number_format($item['product_price'], 2) ?></p>
                        <p>Score: <?= number_format($item['product_score'], 2) ?></p>
                        <p>Platform: <?= $item['platform_name'] ?></p>
                        <a href="platform_redirect.php">Link to site</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form action="knapsack.php" method="post">
        <div>
            <label for="items">Items: </label>
            <input type="text" id="items" name="items" placeholder="notebook pencil paper" required>
        </div>
        <div>
            <label for="budget">Budget: </label>
            <input type="number" id="budget" name="budget" placeholder="50.00" required>
        </div>
        <div>
            <input type="checkbox" id="singleProduct" name="singleProduct" value="ENABLED">
            <label for="singleProduct">Enable one item per product type</label>
        </div>
        <div>
            <button type="submit" name="run">Run Algorithm</button>
        </div>
    </form>

    <?php include 'scripts.php' ?>
</body>

</html>