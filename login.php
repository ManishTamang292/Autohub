<?php

require_once 'auth.php';

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'autoescape' => 'html'
]);

$twig->addGlobal(
    'csrf_token',
    $_SESSION['csrf_token']
);

include("db.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    $submitted_csrf_token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($submitted_csrf_token) ||
        !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $submitted_csrf_token)
    ) {
        $errors[] = 'Invalid security token. Please refresh the page and try again.';
    }

    if (empty($errors)) {

        $stmt = $mysqli->prepare(
            "SELECT user_id, username, password
             FROM users
             WHERE username = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            header("Location: index.php");
            exit();

        } else {

            $errors[] = 'Invalid username or password.';
        }
    }
}

echo $twig->render('login.html.twig', [
    'errors' => $errors
]);

exit();