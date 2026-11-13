<?php

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
        AND model LIKE '%$model%'
        AND price >= '$min_price'";

if ($min_price != '') {
    $sql .= " AND price >= '$min_price'";
}

if ($max_price != '') {
    $sql .= " AND price >= '$max_price'";
}

if ($year != '') {
    $sql .= " AND year >= '$year'";
}

if ($fuel_type != '') {
    $sql .= " AND fuel_type >= '$fuel_type'";
}

if ($transmission != '') {
    $sql .= " AND transmission = '$transmission'";
}

if ($body_type != '') {
    $sql .= " AND body_type = '$body_type'";
}

$results = mysqli_query($mysqli, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoHub - Cars</title>

</head>

<body>

<h1>AutoHub</h1>
<h2>Available Cars</h2>
<h3>Search Cars</h3>

<form method="GET">

    <label>Make:</label>
    <input type="text" name="make" value="<?=$make?>">

    <label>Model:</label>
    <input type="text" name="model" value="<?=$model?>">

    <label>Minimum Price:</label>
    <input type="number" name="min_price" value="<?=$min_price?>">

    <label>Maximum Price:</label>
    <input type="number" name="max_price" value="<?=$max_price?>">

    <label>Year:</label>
    <input type="number" name="year" value="<?=$year?>">

    <label>Fuel Type:</label>
    <select name="fuel_type">
        <option value="">Any</option>
        <option value="Petrol" <?=$fuel_type == 'Petrol' ? 'selected' : ''?>>Petrol</option>
        <option value="Diesel" <?=$fuel_type == 'Diesel' ? 'selected' : ''?>>Diesel</option>
        <option value="Hybrid" <?=$fuel_type == 'Hybrid' ? 'selected' : ''?>>Hybrid</option>
        <option value="Electric" <?=$fuel_type == 'Electric' ? 'selected' : ''?>>Electric</option>
    </select>

    <label>Transmission:</label>
    <select name="transmission">
        <option value="">Any</option>
        <option value="Manual" <?=$transmission == 'Manual' ? 'selected' : ''?>>Manual</option>
        <option value="Automatic" <?=$transmission == 'Automatic' ? 'selected' : ''?>>Automatic</option>
    </select>

    <label>Body Type:</label>
    <select name="body_type">
        <option value="">Any</option>
        <option value="Hatchback" <?=$body_type == 'Hatchback' ? 'selected' : ''?>>Hatchback</option>
        <option value="Saloon" <?=$body_type == 'Saloon' ? 'selected' : ''?>>Saloon</option>
        <option value="SUV" <?=$body_type == 'SUV' ? 'selected' : ''?>>SUV</option>
        <option value="Estate" <?=$body_type == 'Estate' ? 'selected' : ''?>>Estate</option>
        <option value="Coupe" <?=$body_type == 'Coupe' ? 'selected' : ''?>>Coupe</option>
        <option value="Convertible" <?=$body_type == 'Convertible' ? 'selected' : ''?>>Convertible</option>
    </select>

    <button type="submit">Search</button>
    <a href="cars.php">Clear Search</a>

</form>

<h3>Quick Search</h3>

<input type="text" id="ajaxSearch" placeholder="Search by make or model">

<div id="ajaxResults"></div>

<table border="1">
    <tr>
        <th>Make</th>
        <th>Model</th>
        <th>Year</th>
        <th>Price</th>
        <th>Mileage</th>
        <th>Details</th>
    </tr>

    <?php while($car = mysqli_fetch_assoc($results)): ?>

        <tr>
            <td><?=$car['make']?></td>
            <td><?=$car['model']?></td>
            <td><?=$car['year']?></td>
            <td>£<?=$car['price']?></td>
            <td><?=$car['mileage']?> miles</td>
            <td>
                <a href="car-details.php?id=<?=$car['car_id']?>">View Details</a>
            </td>
        </tr>

        

    <?php endwhile; ?>

</table>

<script src="js/autohub.js"></script>

</body>
</html>