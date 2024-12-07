<?php

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_role'])) {
    header("Location: /login.php");
    exit();
    header("Location: /index.php");
    exit();
}
