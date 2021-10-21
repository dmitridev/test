<?php
session_start();
include './classes/JWTHelper.php';
include __DIR__ .'/vendor/autoload.php';
use LordDashMe\SimpleCaptcha\Captcha;

$captcha = new Captcha();

$captcha->code();
$captcha->image();

$_SESSION['code'] = $captcha->getCode();

if (array_key_exists('user', $_SESSION)) {
    header("location: index.php");
}
$_SESSION['login_token'] = JWTHelper::generateToken('123456', 300000);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/bootstrap.min.css" />
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container d-flex flex-column justify-content-center align-items-center" style="height:100vh">
        <h1>Вход</h1>
        <form action="signin" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="hidden" name="login_token" value="<?=$_SESSION['login_token']?>" />
                <label for="exampleInputEmail1">Введите имя или email</label>
                <input type="text" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="login или email" required>
                <small id="emailHelp" class="form-text text-muted"></small>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="пароль" required>
            </div>
            <div class="form-group d-flex flex-column">
                <label for="user_captcha_code">капча</label>
                <img src="<?=$captcha->getImage()?>"/>
                <input type="text" name="user_captcha_code"/>
            </div>
            <p>
                Нет аккаунта - <a href="Register.php">Зарегистрироваться</a>
            </p>
            <button type="submit" class="btn btn-primary">Войти</button>
        </form>
        <div>
            <?php
            if (array_key_exists('message', $_SESSION)) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
            ?>
        </div>
    </div>
</body>

</html>