<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: 401.html');
    return;
}
$user = $_SESSION['username'];
