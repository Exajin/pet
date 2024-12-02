<?php
require_once "../PDO-connect.php";

if (!isset($_POST["id"])) {
    header("Location: PDO-productlist.php");
    exit;
}
$id = $_POST["id"];

$sql = "SELECT product.*,
product.name AS p_name,
inventory.price AS p_price,
kind.name AS p_kind,
type.name AS p_type,
pet_age.name AS p_age,
size.name AS p_size,
inventory.productID AS p_ID,
inventory.amount AS p_amount,
inventory.sale AS p_sale,
product.intro AS p_intro,
product.updated_at AS p_updated_at,
product.created_at AS p_created_at
FROM product 
JOIN inventory ON product.id = inventory.product_id
JOIN kind ON product.kind_id = kind.id
JOIN type ON product.type_id = type.id
JOIN pet_age ON product.pet_age_id = pet_age.id
JOIN size ON inventory.size_id = size.id
WHERE product.id=:id
GROUP BY inventory.productID
";

$sql2 = "SELECT product.*,
img.url AS p_img
FROM product
JOIN img ON product.id = img.product_id
WHERE product.id=:id
GROUP BY img.url
";

// exit;

$stmt = $db_host->prepare($sql);
$stmt2 = $db_host->prepare($sql2);

// if (isset($_GET["id"])) {
//     $stmt->bindParam(":id", $id);
//     $stmt2->bindParam(":id", $id);
// }
// exit;
try {
    $stmt->execute(
        [
            "id" => $id
        ]
    );
    $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $productCount = $stmt->rowCount();

    // echo "接收到的資料：<pre>";
    // print_r($product);
    // // print_r($productCount);
    // echo "</pre>";

    $stmt2->execute(
        [
            "id" => $id
        ]
    );
    $img = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $imgCount = $stmt2->rowCount();

    // echo "接收到的資料：<pre>";
    // print_r($imgCount);
    // echo "</pre>";
} catch (PDOException $e) {
    echo "預處理陳述式執行失敗！ <br/>";
    echo "Error: " . $e->getMessage() . "<br/>";
    $db_host = NULL;
    // exit;
}

// exit;

$db_host = NULL;
?>
<!doctype html>
<html lang="en">

<head>
    <title>商品資料</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css.php"); ?>
    <style>
        .box {
            width: 25%;
            height: 97.04px;
        }

        figure {
            min-height: 410px;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if ($productCount > 0): ?>
            <div class="row py-3">
                <div class="col-4">
                    <form action="PDO-productlist.php" method="post">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </form>
                </div>
                <div class="col-8 d-flex justify-content-between align-items-center mb-2">
                    <h3 class="m-0">
                        <?= $product[0]["p_name"] ?>
                    </h3>
                    <form action="PDO-productedit.php?id=<?= $product[0]["id"] ?>" method="post">
                        <input type="hidden" name="id" value="<?= $product[0]["id"] ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </form>
                </div>
                <div class="col-4">
                    <div>
                        <figure class="border mb-1 d-flex justify-content-center">
                            <img class="object-fit-contain w-100" src="<?= $img[0]["p_img"] ?>" alt="" id="mainPic" />
                        </figure>
                        <div class="hstack gap-1 justify-content-start">
                            <?php if ($imgCount <= 4): for ($i = 0; $i < $imgCount; $i++): ?>
                                    <div class="box active overflow-hidden border d-flex align-items-center justify-content-center">
                                        <img class="object-fit-contain w-100" src="<?= $img[$i]["p_img"] ?>" alt="" />
                                    </div>
                                <?php endfor;
                            elseif ($imgCount > 4): for ($i = 0; $i < 4; $i++): ?>
                                    <div class="box active overflow-hidden border d-flex align-items-center justify-content-center">
                                        <img class="object-fit-contain w-100" src="<?= $img[$i]["p_img"] ?>" alt="" />
                                    </div>
                            <?php endfor;
                            endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <table class="table table-bordered">
                        <tr>
                            <th class="col-2">商品售價</th>
                            <th class="col-10">
                                <?= number_format($product[0]["p_price"]) ?>
                            </th>
                        </tr>
                        <tr>
                            <th>商品分類</th>
                            <th>
                                <?= $product[0]["p_kind"] ?>
                            </th>
                        </tr>
                        <tr>
                            <th>商品類型</th>
                            <th>
                                <?= $product[0]["p_type"] ?>
                            </th>
                        </tr>
                        <tr>
                            <th>適用寵物年齡</th>
                            <th>
                                <?= $product[0]["p_age"] ?>
                            </th>
                        </tr>
                        <!-- <tr>
                        <th>適用寵物性別</th>
                        <th></th>
                    </tr> -->
                        <tr>
                            <th>尺寸</th>
                            <th>
                                <?php for ($i = 0; $i < $productCount; $i++): ?>
                                    <div class="col">
                                        <?= $product[$i]["p_size"] ?>尺寸
                                    </div>
                                    <div class="col">
                                        商品編號:<?= $product[$i]["p_ID"] ?>
                                    </div>
                                    <div class="col">
                                        庫存數量:<?= $product[$i]["p_amount"] ?>
                                    </div>
                                    <div class="col">
                                        目前上架數量:<?= $product[$i]["p_sale"] ?>
                                    </div>
                                    <hr>
                                <?php endfor; ?>
                            </th>
                        </tr>
                        <tr>
                            <th>商品說明</th>
                            <th>
                                <?= $product[0]["p_intro"] ?>
                            </th>
                        </tr>
                        <tr>
                            <th>更新日期</th>
                            <th>
                                <?= $product[0]["p_updated_at"] ?>
                            </th>
                        </tr>
                        <tr>
                            <th>上傳日期</th>
                            <th>
                                <?= $product[0]["p_created_at"] ?>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
        <?php else:  ?>
            <h1>查無此商品</h1>
        <?php endif; ?>
    </div>
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous">
    </script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous">
    </script>
    <script>
        const boxes = document.querySelectorAll(".box");
        const mainPic = document.getElementById("mainPic");

        for (let i = 0; i < 4; i++) {
            boxes[i].addEventListener("click", function() {
                let target = this;
                console.log(target);
                mainPic.src = this.children[0].src;
                mainPic.alt = this.children[0].alt;
            });
        }
        //   boxes.addEventListener("click", function () {});
    </script>
</body>

</html>