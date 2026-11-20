<?php

require_once 'auth.php';
requireLogin();

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'autoescape' => 'html'
]);

include("db.php");

$car_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($car_id === false || $car_id === null || $car_id <= 0) {
    http_response_code(400);
    echo "Invalid car ID.";
    exit();
}

$stmt = $mysqli->prepare(
    "SELECT * FROM cars WHERE car_id = ?"
);

$stmt->bind_param("i", $car_id);
$stmt->execute();

$results = $stmt->get_result();
$car = $results->fetch_assoc();

if (!$car) {
    http_response_code(404);
    echo "Car not found.";
    exit();
}

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
    $new_image_filename = $car['image'];

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

    /*
     * Handle optional image replacement.
     * If no new image is selected, the existing image is kept.
     */
    if (
        $image &&
        $image['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($image['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'There was a problem uploading the new image.';
        } else {

            $max_file_size = 5 * 1024 * 1024;

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
    }

    /*
     * Save the new image if one was uploaded.
     */
    if (empty($errors) && $image && $image['error'] === UPLOAD_ERR_OK) {

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

        $new_image_filename = 'cars' . $next_number . '.' . $file_extension;

        $image_path = $upload_directory . $new_image_filename;

        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
            $errors[] = 'Failed to save the uploaded image.';
        }
    }

    if (empty($errors)) {

        $stmt = $mysqli->prepare(
            "UPDATE cars SET
                make = ?,
                model = ?,
                year = ?,
                price = ?,
                mileage = ?,
                fuel_type = ?,
                transmission = ?,
                body_type = ?,
                colour = ?,
                location = ?,
                description = ?,
                image = ?
            WHERE car_id = ?"
        );

        $stmt->bind_param(
            "ssidisssssssi",
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
            $new_image_filename,
            $car_id
        );

        $stmt->execute();

        /*
         * Delete the old image only after the database update succeeds.
         */
        if (
            $image &&
            $image['error'] === UPLOAD_ERR_OK &&
            !empty($car['image']) &&
            $car['image'] !== $new_image_filename
        ) {
            $old_image_path = __DIR__ . '/images/cars/' . $car['image'];

            if (is_file($old_image_path)) {
                unlink($old_image_path);
            }
        }

        header("Location: car-details.php?id=" . $car_id);
        exit();
    }

    $car = array_merge($car, [
        'make' => $make,
        'model' => $model,
        'year' => $year,
        'price' => $price,
        'mileage' => $mileage,
        'fuel_type' => $fuel_type,
        'transmission' => $transmission,
        'body_type' => $body_type,
        'colour' => $colour,
        'location' => $location,
        'description' => $description,
        'image' => $new_image_filename
    ]);
}

echo $twig->render('edit-car.html.twig', [
    'car' => $car,
    'errors' => $errors,
    'csrf_token' => $_SESSION['csrf_token']
]);

exit();