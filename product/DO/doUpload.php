<?php
require_once("../../PDO-connect.php");

if (!isset($_POST["name"])) {
    exit("請循正常管道進入此頁");
}

$name = $_POST["name"];
$product_id = 0;
echo "<pre>";
print_r($_FILES["myFile"]);
echo "</pre>";
exit;

$file = $_FILES["myFile"];

// echo "<pre>";
// print_r($file["name"][0]);
// echo "</pre>";
// echo count($file["name"]);
// exit;


$imgs = [];
if ($file["name"][0] != "") {
    for ($i = 0; $i < count($file["name"]); $i++) {
        // echo "<pre>";
        // print_r($file["name"][$i]);
        // echo "</pre>";
        // exit;
        if ($file["error"][$i] == 0) {
            $j = $i + 1;
            $imageName = time() . "($j)"; //這邊改變命名方式
            $extension = pathinfo($file["name"][$i], PATHINFO_EXTENSION);
            $imgs[] = "img/" . $imageName . ".$extension";
            // echo ($file["tmp_name"][$i]);
            // exit;
            if (move_uploaded_file($file["tmp_name"][$i], "../{$imgs[$i]}")) {
                echo "上傳成功<br>";
            } else {
                echo "上傳失敗<br>";
            }
        } else {
            var_dump($file["error"]);
            exit;
        }
    }
} else {
    $imgs[] = "img/nopic.jpg";
};

// exit;
echo "<pre>";
print_r($imgs);
echo "</pre>";
exit;

// $image = $_FILES["myFile"]["name"];
$i = 1;
foreach ($imgs as $img) {
    $sql = "INSERT INTO img (name,product_id,url,is_deleted) VALUES (:name,:product_id,:img,0)";
    $stmt = $db_host->prepare($sql);
    try {
        $stmt->execute(
            [
                "name" => $name,
                "product_id" => $product_id,
                "img" => $img,
            ]
        );
        echo "新圖片輸入成功($i)<br>";
        $i++;
    } catch (PDOException $e) {
        echo "預處理陳述式執行失敗！ <br/>";
        echo "Error: " . $e->getMessage() . "<br/>";
        $db_host = NULL;
        exit;
    }
}
