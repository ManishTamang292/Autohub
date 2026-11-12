<?php

$mysqli = new mysqli("localhost", "root", "root", "auto hub");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

echo "Database connection successful";

?>