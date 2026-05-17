<?php
session_start();
include 'db_connection.php';

if (isset($_POST['run'])) {
    $capacity = $_POST["budget"];
    $singleProduct = $_POST["singleProduct"];

    $input = $_POST["items"];
    $array = array_values(array_filter(array_map('trim', explode(',', $_POST["items"]))));
    $placeholders = implode(',', array_fill(0, count($array), '?'));

    $sql = "SELECT p.*, b.brand_name, pl.platform_name, pt.product_type
            FROM products p
            JOIN brands b ON b.brand_id = p.brand_id
            JOIN platforms pl ON pl.platform_id = p.platform_id
            JOIN product_types pt ON pt.product_type_id = p.product_type_id
            WHERE pt.product_type IN ($placeholders)";

    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($array)); // "sss"
    $stmt->bind_param($types, ...$array);
    $stmt->execute();
    $result = $stmt->get_result();


    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    $filteredItems = [];
    if ($singleProduct == 'ENABLED') {
        $filteredItems = filterItems($items);
    } else {
        $filteredItems = $items;
    }

    $_SESSION['selectedTypes'] = filterItems($filteredItems);
    $_SESSION['selectedItems'] = knapsack($filteredItems, $capacity);
    $_SESSION['totalPrice'] = totalPrice($_SESSION['selectedItems']);
    header("Location: dashboard.php");
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
