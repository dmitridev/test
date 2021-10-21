<?php
session_start();
include 'classes/User.php';
include 'classes/JWTHelper.php';
require_once 'db/db.php';
if (!$_SESSION['user'])
    header("location: Login.php");

$query = "SELECT * from users";
$users = mysqli_query($connection, $query);

$image = "images/default.png";

if ($_SESSION['user']['avatar']) {
    $image = $_SESSION['user']['avatar'];
}

if (!array_key_exists('token', $_SESSION['user'])) {
    $_SESSION['user']['token'] = JWTHelper::generateToken($_SESSION['user']['key']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/bootstrap.min.css"/>
    <link rel="stylesheet" href="styles/main.css"/>
    <link rel="stylesheet" href="styles/jquery.dataTables.min.css"/>
    <title>Document</title>
</head>

<body>
<script src="js/jquery-3.3.1.slim.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    function showAlert() {
        let token = '<?= $_SESSION['user']['token'] ?>';
        if (token.length != 0)
            alert(token);
    }
</script>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item">
                <button type="button" class="btn" data-toggle="modal" data-target="#token-modal">
                    Токен
                </button>
            </li>
            <li class="nav-item">
                <img src="<?= $image ?>"/>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><?= $_SESSION['user']['email'] ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Logout.php">Выйти<span class="sr-only"></span></a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <h1 class="mb-3">Зарегистрированные пользователи</h1>
    <table class="table" id="sorted-table" data-searching="false">
        <thead>
        <th>id</th>
        <th>Имя</th>
        <th>Email</th>
        <th>Ключ</th>
        <th>Фото</th>
        </thead>
        <tbody>
        <?php foreach ($users as $u) : ?>
            <?php $user = new User($u);
            if ($user->status == 0)
                continue;
            ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= $user->name ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->key ?></td>
                <td><img src="<?= $user->photo ?>" alt="logo"/></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<div class="modal" id="token-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Генерация токена</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Токен</span>
                    </div>
                    <input type="text" class="form-control" value="<?= $_SESSION['user']['token'] ?>"
                           placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="modal-footer" style="white-space:pre-wrap;">
                <a href="generate-token">Сгенерировать токен</a>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/main.js"></script>

</body>

</html>