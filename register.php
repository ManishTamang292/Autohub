<?php

require_once 'auth.php';

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'autoescape' => 'html'
]);

include("db.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $submitted_csrf_token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($submitted_csrf_token) ||
        !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $submitted_csrf_token)
    ) {
        $errors[] = 'Invalid security token. Please refresh the page and try again.';

    } else {

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $captcha = trim($_POST['captcha'] ?? '');

        if ($username === '') {
            $errors[] = 'Username is required.';
        }

        if (
            !isset($_SESSION['captcha_text']) ||
            !hash_equals($_SESSION['captcha_text'], strtoupper($captcha))
        ) {
            $errors[] = 'Incorrect CAPTCHA. Please try again.';
        }

        unset($_SESSION['captcha_text']);

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
}

echo $twig->render('register.html.twig', [
    'errors' => $errors,
    'csrf_token' => $_SESSION['csrf_token']
]);

exit();
