<?php

require_once 'auth.php';
requireLogin();

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

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $make = trim($_POST['make'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $mileage = filter_input(INPUT_POST, 'mileage', FILTER_VALIDATE_INT);
    $fuel_type = $_POST['fuel_type'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $body_type = $_POST['body_type'] ?? '';
    $colour = trim($_POST['colour'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $allowed_fuel_types = [
        'Petrol',
        'Diesel',
        'Hybrid',
        'Electric'
    ];

    $allowed_transmissions = [
        'Manual',
        'Automatic'
    ];

    $allowed_body_types = [
        'Hatchback',
        'Saloon',
        'SUV',
        'Estate',
        'Coupe',
        'Convertible'
    ];

    if ($make === '') {
        $errors[] = 'Make is required.';
    }

    if ($model === '') {
        $errors[] = 'Model is required.';
    }

    if ($year === false || $year === null || $year < 1900 || $year > date('Y') + 1) {
        $errors[] = 'Please enter a valid year.';
    }

    if ($price === false || $price === null || $price < 0) {
        $errors[] = 'Please enter a valid price.';
    }

    if ($mileage === false || $mileage === null || $mileage < 0) {
        $errors[] = 'Please enter a valid mileage.';
    }

    if (!in_array($fuel_type, $allowed_fuel_types, true)) {
        $errors[] = 'Invalid fuel type.';
    }

    if (!in_array($transmission, $allowed_transmissions, true)) {
        $errors[] = 'Invalid transmission.';
    }

    if (!in_array($body_type, $allowed_body_types, true)) {
        $errors[] = 'Invalid body type.';
    }

    if ($colour === '') {
        $errors[] = 'Colour is required.';
    }

    if ($location === '') {
        $errors[] = 'Location is required.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (empty($errors)) {

        $stmt = $mysqli->prepare(
            "UPDATE cars SET
                make = ?,
                model = ?,
                year = ?,
                price = ?,
                mileage = ?,
                fuel_type = ?,
                transmission = ?,
                body_type = ?,
                colour = ?,
                location = ?,
                description = ?
            WHERE car_id = ?"
        );

        $stmt->bind_param(
            "ssidissssssi",
            $make,
            $model,
            $year,
            $price,
            $mileage,
            $fuel_type,
            $transmission,
            $body_type,
            $colour,
            $location,
            $description,
            $car_id
        );

        $stmt->execute();

        header("Location: car-details.php?id=" . $car_id);
        exit();
    }

    $car = array_merge($car, [
        'make' => $make,
        'model' => $model,
        'year' => $year,
        'price' => $price,
        'mileage' => $mileage,
        'fuel_type' => $fuel_type,
        'transmission' => $transmission,
        'body_type' => $body_type,
        'colour' => $colour,
        'location' => $location,
        'description' => $description
    ]);
}

echo $twig->render('edit-car.html.twig', [
    'car' => $car,
    'errors' => $errors
]);

exit();