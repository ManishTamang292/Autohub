<?php

session_start();

$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

$captcha_text = '';

for ($i = 0; $i < 6; $i++) {
    $captcha_text .= $characters[random_int(0, strlen($characters) - 1)];
}

$_SESSION['captcha_text'] = $captcha_text;

$image_width = 180;
$image_height = 60;

$image = imagecreatetruecolor($image_width, $image_height);

$background = imagecolorallocate($image, 245, 245, 245);
$text_colour = imagecolorallocate($image, 30, 30, 30);
$noise_colour = imagecolorallocate($image, 180, 180, 180);

imagefill($image, 0, 0, $background);

for ($i = 0; $i < 80; $i++) {
    imagesetpixel(
        $image,
        random_int(0, $image_width - 1),
        random_int(0, $image_height - 1),
        $noise_colour
    );
}

for ($i = 0; $i < 6; $i++) {
    imageline(
        $image,
        random_int(0, $image_width),
        random_int(0, $image_height),
        random_int(0, $image_width),
        random_int(0, $image_height),
        $noise_colour
    );
}

$font_size = 24;

for ($i = 0; $i < strlen($captcha_text); $i++) {

    imagestring(
        $image,
        5,
        18 + ($i * 25),
        18 + random_int(-4, 4),
        $captcha_text[$i],
        $text_colour
    );
}

header('Content-Type: image/png');

imagepng($image);

imagedestroy($image);