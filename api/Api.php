<?php
include_once '../classes/User.php';
include_once '../classes/JWTHelper.php';
require_once '../db/db.php';
$post_data = file_get_contents('php://input');
$body = json_decode($post_data, true);
$body_key = $body['key'];
$type = $body['type'];
$token = $body['token'];

$query = "SELECT * From users";
$result = mysqli_query($connection, $query);

if (JWTHelper::validateToken($token, $body_key)) {
    $rows = array();
    if ($type == 'json') {
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }

        echo json_encode($rows);
    } else if ($type == 'xml') {
        $res = "<xml version='1.0' encoding='UTF-8'>\n";
        $res .= '<users>';

        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
            $user = new User($r);
            $xml = User::toXML(new User($r));
            $res .= $xml;
        }

        $res .= '</users>';

        echo $res;
    }
} else header('HTTP/1.0 403 Forbidden');
