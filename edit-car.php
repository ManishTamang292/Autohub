<?php

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("db.php");

$car_id = $_GET['id'];

$sql = "SELECT * FROM cars WHERE car_id = $car_id";
$results = mysqli_query($mysqli, $sql);

$car = mysqli_fetch_assoc($results);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $mileage = $_POST['mileage'];
    $fuel_type = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $body_type = $_POST['body_type'];
    $colour = $_POST['colour'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    $sql = "UPDATE cars SET
            make = '$make',
            model = '$model',
            year = '$year',
            price = '$price',
            mileage = '$mileage',
            fuel_type = '$fuel_type',
            transmission = '$transmission',
            body_type = '$body_type',
            colour = '$colour',
            location = '$location',
            description = '$description'
            WHERE car_id = $car_id";

    mysqli_query($mysqli, $sql);

    header("Location: car-details.php?id=$car_id");
    exit();
}

echo $twig->render('edit-car.html.twig', [
    'car' => $car
]);

exit();