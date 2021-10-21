<?php
session_start();
include_once '../classes/JWTHelper.php';
require_once '../db/db.php';

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    $_SESSION['message'] = 'Неверный логин или пароль';
    header("location : Login.php");
}

if (!isset($_POST['login_token']) && !isset($_SESSION['login_token'])) {
    $_SESSION['message'] = "Ошибка сервера.";
    header("Location : Login.php");
    exit;
}

$form_token = $_POST['login_token'];
$csrf_token = $_SESSION['login_token'];

if (!hash_equals($form_token, $csrf_token)) {
    $_SESSION['message'] = 'Ошибка сервера.';
    header("Location: Login.php");
    exit;
}

if ($_POST['user_captcha_code'] != $_SESSION['code']){
    $_SESSION['message'] = 'Неверная капча';
    header("Location: Login.php");
    exit;
}

$name_or_email = $_POST['email'];
$password = md5($_POST['password']);

$query = "SELECT * FROM users where (email='$name_or_email' OR `name`='$name_or_email') AND (`password` = '$password') AND (status = 1)";
$result = mysqli_query($connection, $query);
$count = mysqli_num_rows($result);
print_r($count);
if ($count) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION['user'] = [
        'name' => $user['name'],
        'email' => $user['email'],
        'avatar' => $user['photo'],
        'key' => $user['generated_key'],
        'activation_code' => $user['activation_code']
    ];
    header("Location: index.php");
    $connection->close();
    exit;
} else {
    $_SESSION['message'] = "Такой пользователь не найден или пользователь не активирован";
    $connection->close();
    header("Location: Login.php");
    exit;
}
