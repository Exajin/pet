<?php
require_once "../db_connect.php";

if (!isset($_GET["id"])) {
    header("location:product-list.php");
}

$id = $_GET["id"];
$sql = "SELECT * FROM user_order_product WHERE id=$id";
$result = $conn->query($sql);
$orderData = $result->fetch_assoc();


$sqlDetail = "SELECT  user_order_product_detail.*,product.name,product.price 
FROM user_order_product_detail
JOIN product ON user_order_product_detail.product_id = product.id 
WHERE user_order_product_detail.order_id=$id";
$resultDetail = $conn->query($sqlDetail);
$products = $resultDetail->fetch_all(MYSQLI_ASSOC);


?>

<!doctype html>
<html lang="en">

<head>
    <title>Order Detail</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <?php include("../css.php"); ?>
</head>

<body>
    <?php include("../header.php"); ?>
    <div class="container">
        <h1>訂單內容 #<?= $id ?></h1>
        <div>訂購日期:<?= $orderData["order_time"] ?></div>
        <ul>
            <?php foreach ($products as $product) : ?>
                <li><?= $product["name"] ?> $<?= $product["price"] ?> X <?= $product["amount"] ?></li>
            <?php endforeach; ?>
        </ul>



    </div>
    <?php include("../js.php"); ?>

</body>

</html>