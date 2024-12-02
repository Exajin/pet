<?php

$servername = "localhost";
$username = "dogadmin";
$password = "helloworld";
$dbname = "pet";

try {
    $db_host = new PDO(
        "mysql:host={$servername};dbname={$dbname};charset=utf8",
        $username,
        $password
    );
    // echo "資料庫連線成功";
} catch (PDOException $e) {
    echo "資料庫連線失敗<br>";
    echo $e->getMessage();
    exit;
}

session_start();
