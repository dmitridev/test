<?php
require_once 'db/db.php';
include 'classes/User.php';

$code = $_GET['code'];
$query = "select * from users where activation_code='$code'";
$result = mysqli_query($connection,$query);
if(mysqli_num_rows($result) == 0){
    die("something wrong");
}
if($result){
    $user = new User(mysqli_fetch_assoc($result));
    $id = $user->id;

    $query = "update users Set status=1 where id='$id'";
    $result = mysqli_query($connection,$query);
}

$connection->close();

