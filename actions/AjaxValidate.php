<?php
require_once 'db/db.php';
$body = file_get_contents("php://input");
$body = json_decode($body, true);

$errors = [
    'email' => [
        'result' => true,
        'value' => ''
    ],
    'name' => [
        'result' => true,
        'value' => ''
    ],
    'password' => [
        'result' => true,
        'value' => ''
    ],
    'password_confirm' => [
        'result' => true,
        'value' => ''
    ]
];
$fields = [
    'email' => $body['email'],
    'name' => $body['name'],
    'password' => $body['password'],
    'password_confirm' => $body['password_confirm']
];


if ($fields['password'] != $fields['password_confirm']) {
    $error = [
        'result' => false,
        'value' => 'Пароли не совпадают'
    ];
    $errors['password'] = $errors['password_confirm'] = $error;
}

$query = "SELECT * FROM users where (email = '${fields['email']}')";
$result = mysqli_query($connection, $query);


if ($result && mysqli_num_rows($result) != 0) {
    $errors['email'] = [
        'result' => false,
        'value' => 'Такой email уже зарегистрирован'
    ];
}


$query = "SELECT * FROM users where (`name` = '${fields['name']}')";
$result = mysqli_query($connection, $query);
if ($result && mysqli_num_rows($result) != 0) {
    $errors['name'] = [
        'result' => false,
        'value' => 'Такой логин уже зарегистрирован'
    ];
}


$password = $fields['password'];

$error = [
    'result' => false,
    'value' => 'Длина пароля от 8 до 20 символов(буквы, символы, знаки(@$%)'
];

if (!preg_match('/[0-9]*/', $password)) {
    $errors['password'] = $error;
} else if (!preg_match('/[@$%]{1}/', $password)) {
    $errors['password'] = $error;
} else if (strlen($password) < 8 || strlen($password) > 20) {
    $errors['password'] = $error;
}

$connection->close();
echo json_encode($errors);
