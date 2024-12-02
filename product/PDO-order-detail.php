<?php
require_once "../PDO-connect.php";

// if (!isset($_GET["id"])) {
//     header("location:product-list.php");
// }


$id = $_GET["id"];
$sql = "SELECT * FROM user_order_product WHERE id=$id";
$result = $conn->query($sql);
$orderData = $result->fetch_assoc();


$sql = "SELECT order_list.*,
users.name AS order_user,
coupon.name AS order_coupon,
inventory.name AS order_product,
size.name AS order_size,
inventory.price AS order_price,
order_detail.amount AS order_amount,
order_list.created_at AS order_time,
FROM order_list
JOIN users on order_list.user_id=users.id
JOIN coupon on order_list.coupon_id=coupon.id
JOIN order_detail ON order_list.id = order_detail.order_list_id
JOIN inventory ON order_detail.productID = inventory.productID
JOIN size ON inventory.size_id = size.id
JOIN product ON inventory.product_id = product.id
JOIN img ON product.id = img.product_id
$whereClause
ORDER BY img.product_id
";

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
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

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