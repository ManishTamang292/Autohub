<?php

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoHub - Add Car</title>
</head>

<body>

<h1>AutoHub</h1>
<h2>Add a Car</h2>

<form method="POST">

    <label>Make:</label>
    <input type="text" name="make" required>
    <br><br>

    <label>Model:</label>
    <input type="text" name="model" required>
    <br><br>

    <label>Year:</label>
    <input type="number" name="year" required>
    <br><br>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" required>
    <br><br>

    <label>Mileage:</label>
    <input type="number" name="mileage" required>
    <br><br>

    <label>Fuel type:</label>
    <select name="fuel_type" required>
        <option value="Petrol">Petrol</option>
        <option value="Diesel">Diesel</option>
        <option value="Hybrid">Hybrid</option>
        <option value="Electric">Electric</option>
    </select>
    <br><br>

    <label>Transmission:</label>
    <select name="transmission" required>
        <option value="Manual">Manual</option>
        <option value="Automatic">Automatic</option>
    </select>
    <br><br>

    <label>Body type:</label>
    <select name="body_type" required>
        <option value="Hatchback">Hatchback</option>
        <option value="Saloon">Saloon</option>
        <option value="SUV">SUV</option>
        <option value="Estate">Estate</option>
        <option value="Coupe">Coupe</option>
        <option value="Convertible">Convertible</option>
    </select>
    <br><br>

    <label>Colour:</label>
    <input type="text" name="colour" required>
    <br><br>

    <label>Location:</label>
    <input type="text" name="location" required>
    <br><br>

    <label>Description:</label>
    <br>
    <textarea name="description" rows="5" cols="40" required></textarea>
    <br><br>

    <button type="submit">Add Car</button>

</form>

</body>
</html>