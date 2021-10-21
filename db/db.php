<?php
$connection = mysqli_connect('localhost', 'root', '', 'test');
if (!$connection) {
    die('cannot connect to db');
}
