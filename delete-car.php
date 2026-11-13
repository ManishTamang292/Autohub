<?php

include("db.php");

$car_id = $_GET['id'];

$sql = "DELETE FROM cars WHERE car_id = $car_id";

mysqli_query($mysqli, $sql);

header("Location: cars.php");
exit();

?>