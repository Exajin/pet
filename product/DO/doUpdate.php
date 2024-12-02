<?php
require_once("../db_connect.php");

var_dump($_POST["name"]);
if(!isset($_POST["name"])){
    exit("請循正常管道進入此頁面");
}

$id=$_POST["id"];
$name=$_POST["name"];
$phone=$_POST["phone"];
$email=$_POST["email"];

$sql="UPDATE users SET name='$name', phone='$phone', email='$email' WHERE id='$id'";
echo $sql;


if ($conn->query($sql) === TRUE) {
    echo "更新成功";
} else {
    echo "更新失敗 " . $conn->error;
}


$conn->close();

header("Location: user.php?id=$id");