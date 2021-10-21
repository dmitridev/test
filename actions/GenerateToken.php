<?php
session_start();
$name = $_SESSION['user']['name'];
$key = $_SESSION['user']['key'];

include '../classes/JWTHelper.php';

$_SESSION['user']['token'] = JWTHelper::generateToken($key);
header("location: index.php");