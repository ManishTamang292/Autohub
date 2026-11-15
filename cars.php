<?php

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("db.php");

$make = $_GET['make'] ?? '';
$model = $_GET['model'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$year = $_GET['year'] ?? '';
$fuel_type = $_GET['fuel_type'] ?? '';
$transmission = $_GET['transmission'] ?? '';
$body_type = $_GET['body_type'] ?? '';

$sql = "SELECT * FROM cars
        WHERE make LIKE '%$make%'
        AND model LIKE '%$model%'";

if ($min_price != '') {
    $sql .= " AND price >= '$min_price'";
}

if ($max_price != '') {
    $sql .= " AND price <= '$max_price'";
}

if ($year != '') {
    $sql .= " AND year = '$year'";
}

if ($fuel_type != '') {
    $sql .= " AND fuel_type = '$fuel_type'";
}

if ($transmission != '') {
    $sql .= " AND transmission = '$transmission'";
}

if ($body_type != '') {
    $sql .= " AND body_type = '$body_type'";
}

$results = mysqli_query($mysqli, $sql);

$cars = [];

while ($car = mysqli_fetch_assoc($results)) {
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