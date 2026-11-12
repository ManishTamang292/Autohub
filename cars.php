<?php

include("db.php");

$sql = "SELECT * FROM cars ORDER BY year DESC";
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

<table border="1">
    <tr>
        <th>Make</th>
        <th>Model</th>
        <th>Year</th>
        <th>Price</th>
        <th>Mileage</th>
    </tr>

    <?php while($car = mysqli_fetch_assoc($results)): ?>

        <tr>
            <td><?=$car['make']?></td>
            <td><?=$car['model']?></td>
            <td><?=$car['year']?></td>
            <td>£<?=$car['price']?></td>
            <td><?=$car['mileage']?> miles</td>
        </tr>

    <?php endwhile; ?>

</table>

</body>
</html>