<?php
session_start();
include_once './classes/JWTHelper.php';
include_once __DIR__ . '/vendor/autoload.php';

use LordDashMe\SimpleCaptcha\Captcha;

$captcha = new Captcha();
$captcha->code();
$captcha->image();
$_SESSION['code'] = $captcha->getCode();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/bootstrap.min.css"/>
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
<div class="container d-flex flex-column align-content-center justify-content-center">
    <h1>Регистрация</h1>
    <form id="validation-form" action="actions/SignUp.php" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="name">Введите имя</label>
                <input name="name" class="form-control" aria-describedby="emailHelp" placeholder="login" required>
                <label id="name-error-container" style="color:red"></label>
            </div>
            <div class="form-group col-md-6 p-0 m-0">
                <label for="email">Почта</label>
                <input type="email" name="email" class="form-control" aria-describedby="emailHelp" placeholder="email"
                       required>
                <label id="email-error-container" style="color:red"></label>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="password">Пароль</label>
                <input type="password" name="password" class="form-control" placeholder="пароль" required>
                <label id="password-error-container" style="color:red"></label>
            </div>

            <div class="form-group col-md-6">
                <label for="password">Подтверждение пароля</label>
                <input type="password" name="password_confirm" class="form-control" placeholder="Подтверждение пароля"
                       required>
                <label id="password_confirm-error-container" style="color:red"></label>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label for="key">Ключ</label>
                <div class="key-input">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="key" class="form-control" id="key-id" required/>
                        </div>
                        <div class="col-md-6">
                            <button id="generate-key" type="button" class="form-control btn btn-primary">Сгенерировать
                                ключ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <input type="file" name="file">
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6 d-flex flex-column">
                    <label for="user_captcha_code">Проверка того что вы не робот</label>
                    <img src="<?= $captcha->getImage() ?>"/>
                    <input type="text" name="user_captcha_code"/>
                </div>
            </div>
        </div>


        <p>Есть аккаунт - <a href="Login.php">Войти</a></p>
        <button type="submit" onclick="validation(this)" class="btn btn-primary">Зарегистрироваться</button>
    </form>
    <pre>

        <?php
        if (array_key_exists('message', $_SESSION)) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
        ?>
        </pre>

</div>

<script>
    $(document).ready(function () {
        $("#generate-key").click(() => document.getElementById('key-id').value = Math.random().toString(36));
    });

    async function validation(e) {
        let name = $('input[name="name"]').val();
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();
        let password_confirm = $('input[name="password_confirm"]').val();
        let body = {
            name,
            email,
            password,
            password_confirm
        };
        try {
            const response = await fetch('/validate', {
                headers: {
                    'Access-Control-Allow-Origin': '*'
                },
                method: 'POST',
                body: JSON.stringify(body)
            });
            const errors = await response.json();
            console.log(errors);
            let result = Object.entries(errors)
                .flatMap(([key, value]) => value.result)
                .includes(false);

            Object.entries(errors).forEach(([key, value]) => {
                const {value: v} = value;
                $(`#${key}-error-container`).html(v);
            });

            if (!result) {
                $('#validation-form').submit();
            }
        } catch (e) {
            console.error(e);
        }
    };
</script>
</body>

</html>