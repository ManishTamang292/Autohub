<?php

require_once 'auth.php';
requireLogin();

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'autoescape' => 'html'
]);

include("db.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        echo "Invalid CSRF token.";
        exit();
    }

    $make = trim($_POST['make'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $mileage = filter_input(INPUT_POST, 'mileage', FILTER_VALIDATE_INT);
    $fuel_type = $_POST['fuel_type'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $body_type = $_POST['body_type'] ?? '';
    $colour = trim($_POST['colour'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = $_FILES['image'] ?? null;

    $allowed_fuel_types = [
        'Petrol',
        'Diesel',
        'Hybrid',
        'Electric'
    ];

    $allowed_transmissions = [
        'Manual',
        'Automatic'
    ];

    $allowed_body_types = [
        'Hatchback',
        'Saloon',
        'SUV',
        'Estate',
        'Coupe',
        'Convertible'
    ];

    if ($make === '') {
        $errors[] = 'Make is required.';
    }

    if ($model === '') {
        $errors[] = 'Model is required.';
    }

    if ($year === false || $year === null || $year < 1900 || $year > date('Y') + 1) {
        $errors[] = 'Please enter a valid year.';
    }

    if ($price === false || $price === null || $price < 0) {
        $errors[] = 'Please enter a valid price.';
    }

    if ($mileage === false || $mileage === null || $mileage < 0) {
        $errors[] = 'Please enter a valid mileage.';
    }

    if (!in_array($fuel_type, $allowed_fuel_types, true)) {
        $errors[] = 'Invalid fuel type.';
    }

    if (!in_array($transmission, $allowed_transmissions, true)) {
        $errors[] = 'Invalid transmission.';
    }

    if (!in_array($body_type, $allowed_body_types, true)) {
        $errors[] = 'Invalid body type.';
    }

    if ($colour === '') {
        $errors[] = 'Colour is required.';
    }

    if ($location === '') {
        $errors[] = 'Location is required.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Please upload a car image.';
    } else {
    
        $max_file_size = 5 * 1024 * 1024; // 5 MB
    
        if ($image['size'] > $max_file_size) {
            $errors[] = 'Image must be smaller than 5 MB.';
        }
    
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($image['tmp_name']);
    
        $allowed_image_types = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];
    
        if (!isset($allowed_image_types[$mime_type])) {
            $errors[] = 'Only JPG, PNG and WebP images are allowed.';
        }
    }

    if (empty($errors)) {

        $upload_directory = __DIR__ . '/images/cars/';
    
        $file_extension = $allowed_image_types[$mime_type];

        $next_number = 1;

        while (
            file_exists($upload_directory . 'cars' . $next_number . '.jpg') ||
            file_exists($upload_directory . 'cars' . $next_number . '.png') ||
            file_exists($upload_directory . 'cars' . $next_number . '.webp')
        ) {
            $next_number++;
        }

        $image_filename = 'cars' . $next_number . '.' . $file_extension;

        $image_path = $upload_directory . $image_filename;
    
        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
            $errors[] = 'Failed to save the uploaded image.';
        }
    }
    
    if (empty($errors)) {
    
        $stmt = $mysqli->prepare(
            "INSERT INTO cars
            (make, model, year, price, mileage, fuel_type, transmission, body_type, colour, location, description, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
    
        $stmt->bind_param(
            "ssidisssssss",
            $make,
            $model,
            $year,
            $price,
            $mileage,
            $fuel_type,
            $transmission,
            $body_type,
            $colour,
            $location,
            $description,
            $image_filename
        );
    
        $stmt->execute();
    
        header("Location: cars.php");
        exit();
    }
}

echo $twig->render('add-car.html.twig', [
    'errors' => $errors,
    'csrf_token' => $_SESSION['csrf_token']
]);

exit();