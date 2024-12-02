<?php
require_once("../../PDO-connect.php");

// var_dump($_POST["id"]);
// exit;

if (!isset($_POST["id"])) {
    exit("請循正常管道進入此頁面");
}


$id = $_POST["id"];
$name = $_POST["p_name"];
$price = $_POST["p_price"];
$kind = $_POST["p_kind"];
$type = $_POST["p_type"];
$age = $_POST["p_age"];
$IDs = $_POST["p_ID"];
$amounts = $_POST["p_amount"];
$sales = $_POST["p_sale"];
$intro = $_POST["p_intro"];
$updated_at = date("Y-m-d H:i:s");

// print_r($ID);
// echo $id, "<br>", $name, "<br>", $kind, "<br>", $type, "<br>", $age, "<br>",  $intro, "<br>", $updated_at;
// exit;

$sql = "UPDATE product, inventory
SET product.name=:name,
inventory.price=:price, 
product.kind_id=:kind, 
product.type_id=:type, 
product.pet_age_id=:age, 
product.intro=:intro, 
product.updated_at=:updated_at
WHERE product.id=:id && product.id = inventory.product_id
";


if (isset($amounts)) {
    $i = 0;
    foreach ($amounts as $amount) {
        $sale = $sales[$i];
        $ID = $IDs[$i];
        $sql2 = "UPDATE inventory
    SET inventory.amount=:amount,
    inventory.sale=:sale
    WHERE inventory.productID=:ID
    ";
        $stmt2 = $db_host->prepare($sql2);
        try {
            $stmt2->execute(
                [
                    "ID" => $ID,
                    "amount" => $amount,
                    "sale" => $sale,
                ]
            );
            // echo "新資料輸入成功";
        } catch (PDOException $e) {
            echo "預處理陳述式執行失敗！ <br/>";
            echo "Error: " . $e->getMessage() . "<br/>";
            $db_host = NULL;
            exit;
        }
        $i++;
    }
}
// exit;


$sql3 = "SELECT * 
FROM inventory 
WHERE inventory.product_id=:id";

$stmt = $db_host->prepare($sql);
$stmt3 = $db_host->prepare($sql3);
try {
    $stmt->execute(
        [
            "id" => $id,
            "name" => $name,
            "price" => $price,
            "kind" => $kind,
            "type" => $type,
            "age" => $age,
            "intro" => $intro,
            "updated_at" => $updated_at
        ]
    );
    $stmt3->execute(
        ["id" => $id]
    );

    // echo "新資料輸入成功";
    // $product = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    // echo "接收到的資料:<pre>";
    // print_r($product);
    // echo "</pre>";
} catch (PDOException $e) {
    echo "預處理陳述式執行失敗！ <br/>";
    echo "Error: " . $e->getMessage() . "<br/>";
    $db_host = NULL;
    exit;
}
// exit;


if (isset($_FILES["myFile"])) {
    $file = $_FILES["myFile"];
    if ($file["error"][0] == UPLOAD_ERR_NO_FILE) {
        echo ("未選擇圖片檔案上傳。");
    } else {
        for ($i = 0; $i < count($file["name"]); $i++) {
            // echo "<pre>";
            // print_r($file["name"][$i]);
            // echo "</pre>";
            // exit;
            if ($file["error"][$i] == 0) {
                $j = $i + 1;
                $imageName = date("Ymd") . $kind . $type . "($j)"; //這邊改變命名方式
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

        $k = 1;
        foreach ($imgs as $img) {
            $sql4 = "INSERT INTO img (name,product_id,url,is_deleted) VALUES (:name,:id,:img,0)";
            $stmt4 = $db_host->prepare($sql4);
            try {
                $stmt4->execute(
                    [
                        "name" => $name,
                        "id" => $id,
                        "img" => $img
                    ]
                );
                echo "圖片檔案建立成功($k)<br>";
                $k++;
            } catch (PDOException $e) {
                echo "預處理陳述式執行失敗！ <br/>";
                echo "Error: " . $e->getMessage() . "<br/>";
                $db_host = NULL;
                exit;
            }
        }
    }
};




header("Location: ../PDO-productlist.php");
