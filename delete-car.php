<?php

require_once 'auth.php';
requireLogin();

include("db.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Method Not Allowed.";
    exit();
}

$car_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($car_id === false || $car_id === null || $car_id <= 0) {
    http_response_code(400);
    echo "Invalid car ID.";
    exit();
}

if (
    !isset($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    echo "Invalid CSRF token.";
    exit();
}

$stmt = $mysqli->prepare(
    "DELETE FROM cars WHERE car_id = ?"
);

$stmt->bind_param("i", $car_id);

$stmt->execute();

header("Location: cars.php");
exit();