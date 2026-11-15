<?php

include("../db.php");

$search = $_GET['search'] ?? '';

$searchTerm = "%" . $search . "%";

$stmt = $mysqli->prepare(
    "SELECT * FROM cars
     WHERE make LIKE ?
     OR model LIKE ?"
);

$stmt->bind_param("ss", $searchTerm, $searchTerm);

$stmt->execute();

$results = $stmt->get_result();

$cars = [];

while ($car = $results->fetch_assoc()) {
    $cars[] = $car;
}

header('Content-Type: application/json');

echo json_encode($cars);