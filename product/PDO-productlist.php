<?php
require_once "../PDO-connect.php";

$whereClause = "WHERE product.is_deleted=0";
if (isset($_GET["min"]) && isset($_GET["max"])) {
    $min = $_GET["min"];
    $max = $_GET["max"];
    $whereClause = "WHERE inventory.price BETWEEN :min AND :max AND product.is_deleted=0";
}

$sql = "SELECT product.*,
product.id AS p_id,
p_img.url AS p_img,
product.name AS p_name,
kind.name AS p_kind,
type.name AS p_type,
inventory.price AS p_price,
inventory.sale AS p_sale,
inventory.amount AS p_amount,
DATE(product.updated_at) AS p_updated_at
FROM product 
JOIN p_img ON product.id = p_img.product_id
JOIN kind ON product.kind_id = kind.id
JOIN type ON product.type_id = type.id
JOIN inventory ON product.id = inventory.product_id
$whereClause
GROUP BY product.id
";


$stmt = $db_host->prepare($sql);



if (isset($_GET["min"]) && isset($_GET["max"])) {
    $stmt->bindParam(":min", $min, PDO::PARAM_INT);
    $stmt->bindParam(":max", $max, PDO::PARAM_INT);
}


try {
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $productCount = $stmt->rowCount();
} catch (PDOException $e) {
    echo "預處理陳述式執行失敗！ <br/>";
    echo "Error: " . $e->getMessage() . "<br/>";
    $db_host = NULL;
}


$db_host = NULL;
?>
<!doctype html>
<html lang="en">

<head>
    <title>商品清單</title>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css.php"); ?>
</head>

<body>
    <div class="container mt-3">
        <h1>商品清單</h1>
        <div class="py-2">
            共計<?= $productCount ?>筆商品
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="py-2">
                <form action="">
                    <div class="row g-2 align-item-center">
                        <?php if (isset($_GET["min"])): ?>
                            <div class="col-auto">
                                <a class="btn btn-primary" href="PDO-productlist.php"><i class="fa-solid fa-arrow-left"></i></a>
                            </div>
                        <?php endif; ?>
                        <div class="col-auto">
                            <input type="number" class="form-control text-end" name="min" value="<?= $_GET["min"] ?? "" ?>">
                        </div>
                        <div class="col-auto">
                            ~
                        </div>
                        <div class="col-auto">
                            <input type="number" class="form-control text-end" name="max" value="<?= $_GET["max"] ?? "" ?>">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter-circle-dollar fa-fw"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div>
                <a class="btn btn-primary" href="PDO-createproduct.php"><i class="fa-solid fa-plus"></i> 新增商品</a>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center align-middle row">
                    <td class="text-nowrap col">編號</td>
                    <td class="text-nowrap col">商品圖片</td>
                    <td class="text-nowrap col">商品名稱</td>
                    <td class="text-nowrap col">類型</td>
                    <td class="text-nowrap col">商品類別</td>
                    <td class="text-nowrap col">一般售價</td>
                    <td class="text-nowrap col">上架狀態</td>
                    <td class="text-nowrap col">更新日期</td>
                    <td class="text-nowrap col-3">操作</td>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($products as $product):  ?>
                    <tr class="row">
                        <td class="col text-center d-flex justify-content-center align-items-center">
                            <?= $i ?>
                        </td>
                        <td class="col">
                            <img class="ratio ratio-4x3" src="<?= $product["p_img"] ?>" alt="">
                        </td>
                        <td class="col d-flex align-items-center">
                            <?= $product["p_name"] ?>
                        </td>
                        <td class="col d-flex justify-content-center align-items-center">
                            <?= $product["p_kind"] ?>
                        </td>
                        <td class="col d-flex justify-content-center align-items-center">
                            <?= $product["p_type"] ?>
                        </td>
                        <td class="col d-flex justify-content-center align-items-center">
                            $<?= number_format($product["p_price"]) ?>
                        </td>
                        <td class="saletype col d-flex justify-content-center align-items-center">
                            上架中
                        </td>
                        <td class="col d-flex align-items-center">
                            <?= $product["p_updated_at"] ?>
                        </td>
                        <td class="col-3 d-flex justify-content-center align-items-center">
                            <form class="me-2" action="PDO-product.php" method="post">
                                <input type="hidden" name="id" value="<?= $product["p_id"] ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa-solid fa-pen-to-square text-nowrap">編輯</i>
                                </button>
                            </form>
                            <form class="me-2" action="DO/PDO-doDelete.php" method="post">
                                <input type="hidden" name="id" value="<?= $product["p_id"] ?>">
                                <button class="btn btn-danger" type="submit">
                                    <i class="fa-solid fa-trash text-nowrap">刪除</i>
                                </button>
                            </form>
                            <button class="sale btn btn-success" type="submit">
                                <i class="salebtn fa-solid fa-rotate text-nowrap">上架</i>
                            </button>
                        </td>
                    </tr>
                <?php $i++;
                endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        const sale = document.querySelectorAll(".sale");
        const saletype = document.querySelectorAll(".saletype");
        const salebtn = document.querySelectorAll(".salebtn");

        for (let i = 0; i < sale.length; i++) {
            sale[i].addEventListener("click", () => {
                if (sale[i].classList.contains("btn-success")) {
                    sale[i].classList.remove("btn-success");
                    sale[i].classList.add("btn-secondary");
                    salebtn[i].textContent = "下架";
                    saletype[i].textContent = "已下架";
                } else {
                    sale[i].classList.remove("btn-secondary");
                    sale[i].classList.add("btn-success");
                    salebtn[i].textContent = "上架";
                    saletype[i].textContent = "上架中";
                }
            });
        }
    </script>
</body>

</html>