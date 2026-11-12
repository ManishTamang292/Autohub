<?php

include("db.php");

$car_id = $_GET['id'];

$sql = "SELECT * FROM cars WHERE car_id = $car_id";
$results = mysqli_query($mysqli, $sql);

$car = mysqli_fetch_assoc($results);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoHub - Car Details</title>
</head>

<body>

<h1>AutoHub</h1>

<h2><?=$car['make']?> <?=$car['model']?></h2>

<p><strong>Year:</strong> <?=$car['year']?></p>
<p><strong>Price:</strong> £<?=$car['price']?></p>
<p><strong>Mileage:</strong> <?=$car['mileage']?> miles</p>
<p><strong>Fuel:</strong> <?=$car['fuel_type']?></p>
<p><strong>Transmission:</strong> <?=$car['transmission']?></p>
<p><strong>Body type:</strong> <?=$car['body_type']?></p>
<p><strong>Colour:</strong> <?=$car['colour']?></p>
<p><strong>Location:</strong> <?=$car['location']?></p>

<h3>Description</h3>
<p><?=$car['description']?></p>

<a href="cars.php">Back to cars</a>

</body>
</html>