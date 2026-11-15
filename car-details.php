<?php

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("db.php");

$car_id = $_GET['id'];

$sql = "SELECT * FROM cars WHERE car_id = $car_id";
$results = mysqli_query($mysqli, $sql);

$car = mysqli_fetch_assoc($results);

echo $twig->render('car-details.html.twig', [
    'car' => $car
]);

exit();