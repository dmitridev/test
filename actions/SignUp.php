<?php
session_start();
require_once '../db/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];
$key = $_POST['key'];

$md5_password = md5($password);
$md5_password_confirm = md5($password_confirm);

$activation_code = uniqid('', true);

if ($_POST['user_captcha_code'] != $_SESSION['code']) {
    $_SESSION['message'] = 'Неверная капча';
    header("Location: Register.php");
    exit;
}

if (hash_equals($md5_password, $md5_password_confirm)) {
    $file = $_FILES['file'];
    $file_curr_path = '';

    if (array_key_exists('tmp_name', $file)) {
        $path = "uploads/";
        $file_curr_path = $path . time() . $file['name'];

        if (!move_uploaded_file($file['tmp_name'], $file_curr_path)) {
            die("something wrong, cannot upload file!");
        }
    }

    $query = "INSERT INTO users(`name`,email,`password`,generated_key,photo,activation_code) values('$name','$email','$md5_password','$key','$file_curr_path','$activation_code')";
    $result = mysqli_query($connection, $query);

    $to = $_POST['email'];
    $code = $_GET['code'];

    $headers = "From: <anti.hamster.dt@gmail.com>\r\n";
    $headers .= "Reply-To: <$to>\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $subject = "Активация учётной записи";
    $folder = '';
    if (strstr($_SERVER['REQUEST_URI'], 'test'))
        $folder = '/test';
    $host = $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $folder;

    $activation = "/Activation.php?code=$activation_code";
    $protocol = (isset($_SERVER['HTTPS']) && isset($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
    $full_url = $protocol . $host . $activation;


    $message = "<html><body><div>Для активации перейдите по ссылке: $full_url</div></body></html>";

    try {
        $result = mail($to, $subject, $message, $headers);
    } catch (Exception $e) {
        echo $e;
    }

    $_SESSION['message'] = "Для завершения регистрации откройте письмо по ссылке.";
    header("Location: Login.php");
}
