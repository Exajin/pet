<?php
require_once("../../PDO-connect.php");

if (!isset($_POST["id"])) {
    die("請循正常管道進入此頁面");
    header("Location: ../PDO-productlist.php");
}

$id = $_POST["id"];
$sql = "UPDATE product SET is_deleted=1 WHERE id=:id";

$stmt = $db_host->prepare($sql);
try {
    $stmt->execute(
        [
            "id" => $id
        ]
    );
    echo "商品刪除成功";
} catch (PDOException $e) {
    echo "預處理陳述式執行失敗！ <br/>";
    echo "Error: " . $e->getMessage() . "<br/>";
    $db_host = NULL;
    exit;
}

header("Location: ../PDO-productlist.php");
