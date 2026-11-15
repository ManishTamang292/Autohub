<?php

require_once 'auth.php';

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("db.php");

$car_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($car_id === false || $car_id === null || $car_id <= 0) {
    http_response_code(400);
    echo "Invalid car ID.";
    exit();
}

$stmt = $mysqli->prepare(
    "SELECT * FROM cars WHERE car_id = ?"
);

$stmt->bind_param("i", $car_id);

$stmt->execute();

$results = $stmt->get_result();

$car = $results->fetch_assoc();

if (!$car) {
    http_response_code(404);
    echo "Car not found.";
    exit();
}

echo $twig->render('car-details.html.twig', [
    'car' => $car,
    'csrf_token' => $_SESSION['csrf_token']
]);

exit();