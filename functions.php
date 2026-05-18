<?php
function totalPrice($items) {
    $total = 0;

    foreach ($items as $item) {
        $total += $item['product_price'];
    }

    return $total;
}