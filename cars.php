<?php

require_once 'auth.php';

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'autoescape' => 'html'
]);

include("db.php");

$make = trim($_GET['make'] ?? '');
$model = trim($_GET['model'] ?? '');
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$year = $_GET['year'] ?? '';
$fuel_type = $_GET['fuel_type'] ?? '';
$transmission = $_GET['transmission'] ?? '';
$body_type = $_GET['body_type'] ?? '';

$sql = "SELECT * FROM cars
        WHERE make LIKE ?
        AND model LIKE ?";

$params = [
    "%" . $make . "%",
    "%" . $model . "%"
];

$types = "ss";

if ($min_price !== '') {

    $min_price_value = filter_var($min_price, FILTER_VALIDATE_FLOAT);

    if ($min_price_value !== false && $min_price_value >= 0) {

        $sql .= " AND price >= ?";
        $params[] = $min_price_value;
        $types .= "d";
    }
}

if ($max_price !== '') {

    $max_price_value = filter_var($max_price, FILTER_VALIDATE_FLOAT);

    if ($max_price_value !== false && $max_price_value >= 0) {

        $sql .= " AND price <= ?";
        $params[] = $max_price_value;
        $types .= "d";
    }
}

if ($year !== '') {

    $year_value = filter_var($year, FILTER_VALIDATE_INT);

    if ($year_value !== false && $year_value >= 1900 && $year_value <= date('Y') + 1) {

        $sql .= " AND year = ?";
        $params[] = $year_value;
        $types .= "i";
    }
}

$allowed_fuel_types = [
    'Petrol',
    'Diesel',
    'Hybrid',
    'Electric'
];

if (in_array($fuel_type, $allowed_fuel_types, true)) {

    $sql .= " AND fuel_type = ?";
    $params[] = $fuel_type;
    $types .= "s";
}

$allowed_transmissions = [
    'Manual',
    'Automatic'
];

if (in_array($transmission, $allowed_transmissions, true)) {

    $sql .= " AND transmission = ?";
    $params[] = $transmission;
    $types .= "s";
}

$allowed_body_types = [
    'Hatchback',
    'Saloon',
    'SUV',
    'Estate',
    'Coupe',
    'Convertible'
];

if (in_array($body_type, $allowed_body_types, true)) {

    $sql .= " AND body_type = ?";
    $params[] = $body_type;
    $types .= "s";
}

$stmt = $mysqli->prepare($sql);

$stmt->bind_param($types, ...$params);

$stmt->execute();

$results = $stmt->get_result();

$cars = [];

while ($car = $results->fetch_assoc()) {
    $cars[] = $car;
}

echo $twig->render('cars.html.twig', [
    'cars' => $cars,
    'make' => $make,
    'model' => $model,
    'min_price' => $min_price,
    'max_price' => $max_price,
    'year' => $year,
    'fuel_type' => $fuel_type,
    'transmission' => $transmission,
    'body_type' => $body_type
]);

exit();