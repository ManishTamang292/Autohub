<?php

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("db.php");

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

    $sql = "INSERT INTO cars 
            (make, model, year, price, mileage, fuel_type, transmission, body_type, colour, location, description)
            VALUES 
            ('$make', '$model', '$year', '$price', '$mileage', '$fuel_type', '$transmission', '$body_type', '$colour', '$location', '$description')";

    mysqli_query($mysqli, $sql);

    header("Location: cars.php");
    exit();
}

echo $twig->render('add-car.html.twig');

exit();