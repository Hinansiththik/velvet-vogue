<?php
include 'env.php';

$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$database = $_ENV['DB_NAME'];

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>