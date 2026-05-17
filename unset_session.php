<?php

    session_start();

    $temp_name = $_SESSION['username'];
    $temp_id = $_SESSION['user_id'];

    session_unset();
    $_SESSION['username'] = $temp_name;
    $_SESSION['user_id'] = $temp_id;

    header("Location: dashboard.php");

