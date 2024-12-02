<?php
require_once "../PDO-connect.php";

$titleType = "";
$whereClause = "";
// if (isset($_GET["date"])) {
//     $date = $_GET["date"];
//     $whereClause = "WHERE user_order.order_date='$date'";
//     $titleType = $date;
// }
// if (isset($_GET["user"])) {
//     $user_id = $_GET["user"];
//     $whereClause = "WHERE user_order.user_id='$user_id'";
// }
// if (isset($_GET["product"])) {
//     $product_id = $_GET["product"];
//     $whereClause = "WHERE user_order.product_id='$product_id'";
// }
// if (isset($_GET["start"]) && isset($_GET["end"])) {
//     $start = $_GET["start"];
//     $end = $_GET["end"];
//     $whereClause = "WHERE user_order.order_date BETWEEN '$start' AND '$end'";
// }


$sql = "SELECT order_list.*,
order_list.orderlistID AS order_ID,
-- coupon.name AS order_coupon,
product.name AS order_product,
inventory.price AS order_price,
order_detail.amount AS order_amount,
DATE(order_list.created_at) AS order_date
FROM order_list
-- JOIN coupon on order_list.coupon_id=coupon.id
JOIN order_detail ON order_list.id = order_detail.order_list_id
JOIN inventory ON order_detail.productID = inventory.productID
JOIN product ON inventory.product_id = product.id
$whereClause
";


$stmt = $db_host->prepare($sql);

try {
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $orderCount = $stmt->rowCount();
} catch (PDOException $e) {
    echo "預處理陳述式執行失敗！ <br/>";
    echo "Error: " . $e->getMessage() . "<br/>";
    $db_host = NULL;
}

$title = "$titleType 訂單列表";
$db_host = NULL;
?>

<!doctype html>
<html lang="en">

<head>
    <title><?= $title ?></title>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css.php"); ?>
</head>

<body>
    <div class="container mt-3">
        <div class="d-flex align-items-center">
            <?php if (isset($_GET["user"]) || isset($_GET["date"]) || isset($_GET["product"]) || isset($_GET["start"])): ?>
                <a class="btn btn-primary me-2" href="PDO-order-list.php"><i class="fa-solid fa-arrow-left"></i></a>
            <?php endif; ?>
            <h1><?= $title ?></h1>
        </div>
        <div class="py-2">
            <form action="">
                <div class="row g-3 align-center">
                    <div class="col-auto">
                        <input type="date" class="form-control" name="start" value="<?= $_GET["start"] ?? "" ?>">
                    </div>
                    <div class="col-auto">
                        ~
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control" name="end" value="<?= $_GET["end"] ?? date('Y-m-d') ?>">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">編號</th>
                    <th class="text-center">品名</th>
                    <th class="text-center">價格</th>
                    <th class="text-center">數量</th>
                    <th class="text-center">小計</th>
                    <th class="text-center">訂購日期</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <?php if (count($order["order_ID"]) > 1): ?>

                            <?php else: ?>
                                <?= $order["order_ID"] ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $order["order_product"] ?>
                        </td>
                        <td class="text-end">
                            <?= number_format($order["order_price"]) ?>
                        </td>
                        <td class="text-center">
                            <?= $order["order_amount"] ?>
                        </td>
                        <td class="text-end">
                            <?php $subtotal = $order["order_price"] * $order["order_amount"];
                            echo number_format($subtotal);
                            $total += $subtotal ?>
                        </td>
                        <td>
                            <?= $order["order_date"] ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-end">總銷售額:$<?= number_format($total) ?></div>
    </div>
</body>

</html>