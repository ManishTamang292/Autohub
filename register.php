<?php

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("db.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (empty($errors)) {

        $password_hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $mysqli->prepare(
            "INSERT INTO users (username, email, password)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "sss",
            $username,
            $email,
            $password_hash
        );

        try {

            if ($stmt->execute()) {
        
                header("Location: login.php");
                exit();
            }
        
        } catch (mysqli_sql_exception $e) {
        
            $errors[] = 'Username or email may already be registered.';
        }
    }
}

echo $twig->render('register.html.twig', [
    'errors' => $errors
]);

exit();