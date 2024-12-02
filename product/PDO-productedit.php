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

$sql3 = "SELECT kind.*
FROM kind
";

$sql4 = "SELECT type.*
FROM type
";

$sql5 = "SELECT pet_age.*
FROM pet_age
";



$sql6 = "SELECT size.*
FROM size
";


$stmt = $db_host->prepare($sql);
$stmt2 = $db_host->prepare($sql2);
$stmt3 = $db_host->prepare($sql3);
$stmt4 = $db_host->prepare($sql4);
$stmt5 = $db_host->prepare($sql5);
$stmt6 = $db_host->prepare($sql6);
if (isset($_GET["id"])) {
    $stmt->bindParam(":id", $id);
    $stmt2->bindParam(":id", $id);
}

try {
    $stmt->execute();
    $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $productCount = $stmt->rowCount();


    $stmt2->execute();
    $img = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $imgCount = $stmt2->rowCount();

    $stmt3->execute();
    $kinds = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    $kindsCount = $stmt3->rowCount();

    $stmt4->execute();
    $types = $stmt4->fetchAll(PDO::FETCH_ASSOC);
    $typesCount = $stmt4->rowCount();

    $stmt5->execute();
    $ages = $stmt5->fetchAll(PDO::FETCH_ASSOC);
    $agesCount = $stmt5->rowCount();


    $stmt6->execute();
    $sizes = $stmt6->fetchAll(PDO::FETCH_ASSOC);
    $sizesCount = $stmt6->rowCount();
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
    <title>編輯商品資料</title>

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

        .content.overflow-hidden {
            position: relative;

            img {
                object-fit: cover;
                width: 23%;

                margin: 2.5px;
                border: solid 1px #000;

                &:hover {
                    cursor: pointer;
                    opacity: 0.6;
                    transition: 0.5s;
                }
            }
        }
    </style>
</head>

<body>
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog model-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        刪除商品資料
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    此操作將會刪除商品資料,確定嗎?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        取消
                    </button>
                    <form action="DO/PDO-doDelete.php" method="post">
                        <input type="hidden" name="id" value="<?= $product[0]["id"] ?>">
                        <button class="btn btn-danger" type="submit">
                            確認
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="mt-3">
            <form action="PDO-product.php" method="post">
                <input type="hidden" name="id" value="<?= $product[0]["id"] ?>">
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </form>
        </div>
        <?php if ($productCount > 0): ?>
            <form action="DO/PDO-doUpdate.php" method="post" enctype="multipart/form-data">
                <div class="row py-3">
                    <div class="col-4">
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
                                    <div class="box active overflow-hidden border align-middle d-flex align-items-center justify-content-center">
                                        <img class="object-fit-contain w-100" src="<?= $img[$i]["p_img"] ?>" alt="" />
                                    </div>
                            <?php endfor;
                            endif; ?>
                        </div>
                        <div class="my-3">
                            <label for="" class="form-label">選擇商品圖片：</label>
                            <input class="form-control" type="file" name="myFile[]" accept="image/*" multiple>
                        </div>
                        <p class="text-danger"></p>
                        <h5>圖片預覽</h5>
                        <div class="hstack gap-1 justify-content-start">
                            <div class="content overflow-hidden">
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="col-8">
                        <table class="table table-bordered">
                            <input type="hidden" name="id" value="<?= $product[0]["id"] ?>">
                            <tr>
                                <th class="col-3 align-middle">商品名稱</th>
                                <th class="col-9">
                                    <input type="text" class="form-control" name="p_name" id="" value="<?= $product[0]["p_name"] ?>">
                                </th>
                            </tr>
                            <tr>
                                <th>商品售價</th>
                                <th class="d-flex align-items-center text-nowrap">
                                    $<input type="number" class="form-control" name="p_price" id="" value="<?= $product[0]["p_price"] ?>">
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">商品分類</th>
                                <th>
                                    <select class="form-select" name="p_kind" id="">
                                        <option selected value="<?= $product[0]["kind_id"] ?>"><?= $product[0]["p_kind"] ?></option>
                                        <?php for ($i = 0; $i < $kindsCount; $i++): if ($kinds[$i]["name"] == $product[0]["p_kind"]): continue;
                                            endif; ?>
                                            <option value="<?= $kinds[$i]["id"] ?>"><?= $kinds[$i]["name"] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">商品類型</th>
                                <th>
                                    <select class="form-select" name="p_type" id="">
                                        <option selected value="<?= $product[0]["type_id"] ?>"><?= $product[0]["p_type"] ?></option>
                                        <?php for ($i = 0; $i < $typesCount; $i++): if ($types[$i]["name"] == $product[0]["p_type"]): continue;
                                            endif; ?>
                                            <option value="<?= $types[$i]["id"] ?>"><?= $types[$i]["name"] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">適用狗狗年齡層</th>
                                <th>
                                    <select class="form-select" name="p_age" id="">
                                        <option selected value="<?= $product[0]["pet_age_id"] ?>"><?= $product[0]["p_age"] ?></option>
                                        <?php for ($i = 0; $i < $agesCount; $i++): if ($ages[$i]["name"] == $product[0]["p_age"]): continue;
                                            endif; ?>
                                            <option value="<?= $ages[$i]["id"] ?>"><?= $ages[$i]["name"] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th>尺寸</th>
                                <th>
                                    <?php for ($i = 0; $i < $productCount; $i++): ?>
                                        <div class="col">
                                            <?= $product[$i]["p_size"] ?>尺寸
                                        </div>
                                        <div class="col">
                                            商品編號:<?= $product[$i]["p_ID"] ?>
                                            <input type="hidden" name="p_ID[]" value="<?= $product[$i]["p_ID"] ?>">
                                        </div>
                                        <div class="col d-flex align-items-center text-nowrap">
                                            庫存數量:
                                            <input type="number" class="form-control" name="p_amount[]" id="" value="<?= $product[$i]["p_amount"] ?>">
                                        </div>
                                        <div class="col d-flex align-items-center text-nowrap">
                                            目前上架數量:
                                            <input type="number" class="form-control" name="p_sale[]" id="" value="<?= $product[$i]["p_sale"] ?>">
                                        </div>
                                        <hr>
                                    <?php endfor; ?>
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">商品說明</th>
                                <th>
                                    <textarea class="form-control" name="p_intro" rows="3" id=""><?= $product[0]["p_intro"] ?></textarea>
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">更新日期</th>
                                <th>
                                    <?= $product[0]["p_updated_at"] ?>
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">上傳日期</th>
                                <th>
                                    <?= $product[0]["p_created_at"] ?>
                                </th>
                            </tr>
                        </table>
                        <div class="d-flex justify-content-end ">
                            <div>
                                <button type="submit" class="me-2 btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                </button>
                            </div>
                            <div>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal" type="button">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

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

        const input_file = document.querySelector("input[type='file']");
        const content = document.querySelector(".content");
        const alert = document.querySelector(".text-danger");

        for (let i = 0; i < boxes.length; i++) {
            boxes[i].addEventListener("click", function() {
                let target = this;
                console.log(target);
                mainPic.src = this.children[0].src;
                mainPic.alt = this.children[0].alt;
            });
        };

        input_file.addEventListener("change", e => {
            console.log(e.currentTarget.files.length); // 打印選擇的文件數量
            for (let j = 0; j < e.currentTarget.files.length; j++) {
                const file = e.currentTarget.files[j];
                if (file.type.startsWith("image/")) { // 確保選擇的是圖片
                    alert.textContent = "";
                    const node = document.createElement("img"); // 創建一個 <img> 元素
                    const src = URL.createObjectURL(file); // 創建指向圖片的臨時 URL
                    node.src = src; // 設定圖片的源
                    content.append(node); // 把圖片添加到 <div class="content"> 中
                    const img = document.querySelectorAll("img");
                    img[j].addEventListener('click', () => {
                        img[j].remove(); // 從畫面上移除
                    });
                } else {
                    alert.textContent = "僅能上傳圖片檔案";
                    input_file.value = null;
                }
            }
        });
    </script>
</body>

</html>