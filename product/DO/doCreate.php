<?php

require_once("../db_connect.php");

$name = $_POST["name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$now = date("Y-m-d H:i:s");


$sql = "INSERT INTO users (name, phone, email,created_at)
    VALUES ('$name', '$phone', '$email','$now')";


if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新資料輸入成功,id為$last_id";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();

header("Location: create_user.php");
