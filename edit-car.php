<?php

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoHub - Edit Car</title>
</head>

<body>

<h1>AutoHub</h1>
<h2>Edit Car</h2>

<form method="POST">

    <label>Make:</label>
    <input type="text" name="make" value="<?=$car['make']?>" required>
    <br><br>

    <label>Model:</label>
    <input type="text" name="model" value="<?=$car['model']?>" required>
    <br><br>

    <label>Year:</label>
    <input type="number" name="year" value="<?=$car['year']?>" required>
    <br><br>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" value="<?=$car['price']?>" required>
    <br><br>

    <label>Mileage:</label>
    <input type="number" name="mileage" value="<?=$car['mileage']?>" required>
    <br><br>

    <label>Fuel type:</label>
    <select name="fuel_type" required>
        <option value="Petrol" <?=$car['fuel_type'] == 'Petrol' ? 'selected' : ''?>>Petrol</option>
        <option value="Diesel" <?=$car['fuel_type'] == 'Diesel' ? 'selected' : ''?>>Diesel</option>
        <option value="Hybrid" <?=$car['fuel_type'] == 'Hybrid' ? 'selected' : ''?>>Hybrid</option>
        <option value="Electric" <?=$car['fuel_type'] == 'Electric' ? 'selected' : ''?>>Electric</option>
    </select>
    <br><br>

    <label>Transmission:</label>
    <select name="transmission" required>
        <option value="Manual" <?=$car['transmission'] == 'Manual' ? 'selected' : ''?>>Manual</option>
        <option value="Automatic" <?=$car['transmission'] == 'Automatic' ? 'selected' : ''?>>Automatic</option>
    </select>
    <br><br>

    <label>Body type:</label>
    <select name="body_type" required>
        <option value="Hatchback" <?=$car['body_type'] == 'Hatchback' ? 'selected' : ''?>>Hatchback</option>
        <option value="Saloon" <?=$car['body_type'] == 'Saloon' ? 'selected' : ''?>>Saloon</option>
        <option value="SUV" <?=$car['body_type'] == 'SUV' ? 'selected' : ''?>>SUV</option>
        <option value="Estate" <?=$car['body_type'] == 'Estate' ? 'selected' : ''?>>Estate</option>
        <option value="Coupe" <?=$car['body_type'] == 'Coupe' ? 'selected' : ''?>>Coupe</option>
        <option value="Convertible" <?=$car['body_type'] == 'Convertible' ? 'selected' : ''?>>Convertible</option>
    </select>
    <br><br>

    <label>Colour:</label>
    <input type="text" name="colour" value="<?=$car['colour']?>" required>
    <br><br>

    <label>Location:</label>
    <input type="text" name="location" value="<?=$car['location']?>" required>
    <br><br>

    <label>Description:</label>
    <br>
    <textarea name="description" rows="5" cols="40" required><?=$car['description']?></textarea>
    <br><br>

    <button type="submit">Update Car</button>

</form>

<br>


<a href="car-details.php?id=<?=$car['car_id']?>">Cancel</a>

</body>
</html>