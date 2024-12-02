<?php
require_once("../../PDO-connect.php");

if (!isset($_POST["p_name"])) {
    die("請循正常管道進入此頁面");
    header("Location: ../PDO-productlist.php");
}

$name = $_POST["p_name"];
$intro = $_POST["p_intro"];
$time = date("Y-m-d H:i:s");
$kind = $_POST["p_kind"];
$type = $_POST["p_type"];
$imgs = [];
$age = $_POST["p_age"];
$price = $_POST["p_price"];
$sizes = $_POST["p_size"];
$amounts = $_POST["p_amount"];
$sales = $_POST["p_sale"];


if (isset($_FILES["myFile"])) {
    $file = $_FILES["myFile"];
    for ($i = 0; $i < count($file["name"]); $i++) {
        if ($file["error"][$i] == 0) {
            $j = $i + 1;
            $imageName = date("Ymd") . $kind . $type . "($j)"; //這邊改變命名方式
            $extension = pathinfo($file["name"][$i], PATHINFO_EXTENSION);
            $imgs[] = "img/" . $imageName . ".$extension";
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


$sql = "INSERT INTO product
(product.name, 
product.intro,
product.updated_at, 
product.kind_id, 
product.type_id,
product.pet_age_id,
product.created_at,
product.is_deleted)
VALUES (:name,:intro,:time,:kind,:type,:age,:time,0)
";


$stmt = $db_host->prepare($sql);
try {
    $stmt->execute(
        [
            "name" => $name,
            "intro" => $intro,
            "time" => $time,
            "kind" => $kind,
            "type" => $type,
            "age" => $age
        ]
    );
    $product_id = $db_host->lastInsertId();
    echo "商品資料建立成功";
} catch (PDOException $e) {
    echo "預處理陳述式執行失敗！ <br/>";
    echo "Error: " . $e->getMessage() . "<br/>";
    $db_host = NULL;
    exit;
}



$i = 0;
foreach ($sizes as $size) {
    $ID = date("Ymd") . $kind . $type . "-" . $size;
    $amount = $amounts[$i];
    $sale = $sales[$i];
    $sql2 = "INSERT INTO inventory
    (inventory.productID,
    inventory.product_id,
    inventory.size_id,
    inventory.amount,
    inventory.sale,
    inventory.price,
    inventory.is_deleted)
    VALUES (:ID,:product_id,:size_id,:amount,:sale,:price,0)
    ";

    $stmt2 = $db_host->prepare($sql2);
    try {
        $stmt2->execute(
            [
                "ID" => $ID,
                "product_id" => $product_id,
                "size_id" => $size,
                "amount" => $amount,
                "sale" => $sale,
                "price" => $price
            ]
        );
        $j = $i + 1;
        echo "庫儲資料建立成功($j)<br>";
    } catch (PDOException $e) {
        echo "預處理陳述式執行失敗！ <br/>";
        echo "Error: " . $e->getMessage() . "<br/>";
        $db_host = NULL;
        exit;
    }
    $i++;
}


$i = 1;
foreach ($imgs as $img) {
    $sql3 = "INSERT INTO img (name,product_id,url,is_deleted) VALUES (:name,:product_id,:img,0)";
    $stmt3 = $db_host->prepare($sql3);
    try {
        $stmt3->execute(
            [
                "name" => $name,
                "product_id" => $product_id,
                "img" => $img,
            ]
        );
        echo "圖片檔案建立成功($i)<br>";
        $i++;
    } catch (PDOException $e) {
        echo "預處理陳述式執行失敗！ <br/>";
        echo "Error: " . $e->getMessage() . "<br/>";
        $db_host = NULL;
        exit;
    }
}

header("Location: ../PDO-productlist.php");
