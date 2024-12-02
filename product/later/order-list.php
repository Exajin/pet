<?php
require_once "../db_connect.php";

$titleType = "";
$whereClause = "";
if (isset($_GET["date"])) {
    $date = $_GET["date"];
    $whereClause = "WHERE user_order.order_date='$date'";
    $titleType = $date;
}
if (isset($_GET["user"])) {
    $user_id = $_GET["user"];
    $whereClause = "WHERE user_order.user_id='$user_id'";
}
if (isset($_GET["product"])) {
    $product_id = $_GET["product"];
    $whereClause = "WHERE user_order.product_id='$product_id'";
}
if (isset($_GET["start"]) && isset($_GET["end"])) {
    $start = $_GET["start"];
    $end = $_GET["end"];
    $whereClause = "WHERE user_order.order_date BETWEEN '$start' AND '$end'";
}
$sql = "SELECT user_order.*,product.name AS product_name,product.price ,users.name  AS user_name  
FROM user_order

JOIN product ON user_order.product_id = product.id
JOIN users ON user_order.user_id=users.id
$whereClause

ORDER BY user_order.order_date DESC
";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_GET["user"])) {
    $titleType = $rows[0]["user_name"];
}
if (isset($_GET["product"])) {
    $titleType = $rows[0]["product_name"];
}
$title = "$titleType 訂單列表";

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
                <a class="btn btn-primary me-2" href="order-list.php"><i class="fa-solid fa-arrow-left"></i></a>
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
                    <th class="text-center">訂購日期</th>
                    <th class="text-center">品名</th>
                    <th class="text-center">價格</th>
                    <th class="text-center">數量</th>
                    <th class="text-center">小計</th>
                    <th class="text-center">訂購者</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0;
                foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row["id"] ?></td>
                        <td><a href="?date=<?= $row["order_date"] ?>">
                                <?= $row["order_date"] ?>
                            </a>
                        </td>
                        <td>
                            <a href="?product=<?= $row["product_id"] ?>"><?= $row["product_name"] ?></a>
                        </td>
                        <td class="text-end"><?= number_format($row["price"]) ?></td>
                        <td class="text-center"><?= $row["amount"] ?></td>
                        <td class="text-end">
                            <?php $subtotal = $row["price"] * $row["amount"];
                            echo number_format($subtotal);
                            $total += $subtotal ?>
                        </td>
                        <td><a href="?user=<?= $row["user_id"] ?>"><?= $row["user_name"] ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-end">總銷售額:$<?= number_format($total) ?></div>
    </div>
</body>

</html>