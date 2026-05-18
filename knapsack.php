<?php
session_start();
include 'db_connection.php';

if (isset($_POST['run'])) {
    // Input handling
    $capacity = $_POST["budget"] ?? $_SESSION["budget"] ?? 0;
    $user_id = $_POST['user_id'] ?? $_SESSION['user_id'];

    $singleProduct = isset($_POST["singleProduct"]);
    $itemsInput = $_POST["items"] ?? $_SESSION["items"] ?? '';

    // Session Storing
    $_SESSION['items'] = $itemsInput;
    $_SESSION['budget'] = $capacity;
    $_SESSION['singleProduct'] = $singleProduct;

    // Process items into an array
    $array = array_values(array_filter(array_map('trim', explode(',', $itemsInput))));

    if (empty($array)) {
        $_SESSION['selectedItems'] = [];
        $_SESSION['selectedTypes'] = [];
        $_SESSION['totalPrice'] = 0;

        header("Location: dashboard.php");
        exit;
    }

    // Develop query
    $placeholders = implode(',', array_fill(0, count($array), '?'));
    $sql = "SELECT p.*, b.brand_name, pl.platform_name, pt.product_type
            FROM products p
            JOIN brands b ON b.brand_id = p.brand_id
            JOIN platforms pl ON pl.platform_id = p.platform_id
            JOIN product_types pt ON pt.product_type_id = p.product_type_id

            LEFT JOIN excluded_items ei
                ON ei.product_id = p.product_id
                AND ei.user_id = ?

            LEFT JOIN included_items ii
                ON ii.product_id = p.product_id
                AND ii.user_id = ?

            WHERE pt.product_type IN ($placeholders)
            AND ei.product_id IS NULL
            AND ii.product_id IS NULL";

    $stmt = $conn->prepare($sql);

    $types = 'ii' . str_repeat('s', count($array));
    $params = array_merge([$user_id, $user_id], $array);

    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $result = $stmt->get_result();

    // Retrieve items
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    // Check if item filter is enabled
    if ($singleProduct) {
        $filteredItems = filterItems($items);
    } else {
        $filteredItems = $items;
    }

    // Run knapsack algorithm
    $_SESSION['selectedTypes'] = filterItems($filteredItems);
    $_SESSION['selectedItems'] = knapsack($filteredItems, $capacity);
    $_SESSION['totalPrice'] = totalPrice($_SESSION['selectedItems']);

    header("Location: dashboard.php");
    exit;
}

function knapsack($items, $capacity) {
    $n = count($items);

    // DP table
    $dp = array_fill(0, $n + 1, array_fill(0, $capacity + 1, 0));

    // Build table
    for ($i = 1; $i <= $n; $i++) {
        $weight = $items[$i-1]['product_price'];
        $value  = $items[$i-1]['product_score'];

        for ($w = 0; $w <= $capacity; $w++) {
            if ($weight <= $w) {
                $dp[$i][$w] = max(
                    $value + $dp[$i-1][$w - $weight],
                    $dp[$i-1][$w]
                );
            } else {
                $dp[$i][$w] = $dp[$i-1][$w];
            }
        }
    }

    // Backtrack to find selected items
    $selected = [];
    $w = $capacity;

    for ($i = $n; $i > 0; $i--) {
        if ($dp[$i][$w] != $dp[$i-1][$w]) {
            $selected[] = $items[$i-1];
            $w -= $items[$i-1]['product_price'];
        }
    }

    return $selected;
}

function filterItems($items) {
    $best = [];

    foreach ($items as $item) {
        $type = $item['product_type'];

        if (
            !isset($best[$type]) ||
            $item['product_score'] > $best[$type]['product_score']
        ) {
            $best[$type] = $item;
        }
    }

    return array_values($best);
}

function totalPrice($items) {
    $total = 0;

    foreach ($items as $item) {
        $total += $item['product_price'];
    }

    return $total;
}

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}
