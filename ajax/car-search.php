<?php

include("../db.php");

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM cars
        WHERE make LIKE '%$search%'
        OR model LIKE '%$search%'";

$results = mysqli_query($mysqli, $sql);

$cars = [];

while ($car = mysqli_fetch_assoc($results)) {
    $cars[] = $car;
}

header('Content-Type: application/json');

echo json_encode($cars);