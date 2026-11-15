<?php

session_start();

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$twig->addGlobal(
    'username',
    $_SESSION['username'] ?? null
);

echo $twig->render('index.html.twig');

exit();