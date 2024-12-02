<?php
require_once "../PDO-connect.php";

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

$stmt3 = $db_host->prepare($sql3);
$stmt4 = $db_host->prepare($sql4);
$stmt5 = $db_host->prepare($sql5);
$stmt6 = $db_host->prepare($sql6);

try {
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
    <title>新增商品資料</title>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css.php"); ?>
    <style>
        .content.overflow-hidden {
            position: relative;

            img {
                object-fit: cover;
                width: 200px;
                height: 200px;
                margin: 5px;
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
    <div class="container">
        <div class="py-2">
            <a href="PDO-productlist.php" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        <h1>新增商品</h1>
        <form action="DO/PDO-doCreate.php" method="post" enctype="multipart/form-data">
            <div class="mb-2">
                <label for="" class="form-label">商品名稱</label>
                <input type="text" class="form-control" name="p_name" id="" required>
            </div>
            <div class="mb-2">
                <label for="" class="form-label">商品售價</label>
                $<input type="number" class="form-control" name="p_price" id="" required>
            </div>
            <div class="d-flex row">
                <div class="my-3 col d-flex text-nowrap align-items-center">
                    <label for="" class="form-label m-0 me-1">商品分類：</label>
                    <select class="form-select" name="p_kind" id="" required>
                        <option selected disabled value="">請選擇商品分類</option>
                        <?php for ($i = 0; $i < $kindsCount; $i++): ?>
                            <option value="<?= $kinds[$i]["id"] ?>"><?= $kinds[$i]["name"] ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="my-3 col d-flex text-nowrap align-items-center">
                    <label for="" class="form-label m-0 me-1">商品類型：</label>
                    <select class="form-select" name="p_type" id="" required>
                        <option selected disabled value="">請選擇商品類型</option>
                        <?php for ($i = 0; $i < $typesCount; $i++): ?>
                            <option value="<?= $types[$i]["id"] ?>"><?= $types[$i]["name"] ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="my-3 col d-flex text-nowrap align-items-center">
                    <label for="" class="form-label m-0 me-1">適用狗狗年齡層：</label>
                    <select class="form-select" name="p_age" id="" required>
                        <option selected disabled value="">請選擇狗狗年齡層</option>
                        <?php for ($i = 0; $i < $agesCount; $i++): ?>
                            <option value="<?= $ages[$i]["id"] ?>"><?= $ages[$i]["name"] ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="d-flex row form-size">
                <div class="col my-3 d-flex text-nowrap align-items-center">
                    <label for="" class="form-label m-0 me-1">商品尺寸：</label>
                    <select class="form-select" name="p_size[]" id="" required>
                        <option selected disabled value="">請選擇尺寸</option>
                        <?php for ($i = 0; $i < $sizesCount; $i++): ?>
                            <option value="<?= $sizes[$i]["id"] ?>"><?= $sizes[$i]["name"] ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col my-3 d-flex text-nowrap align-items-center">
                    <label for="" class="form-label m-0 me-1">庫存數量：</label>
                    <input type="number" class="form-control" name="p_amount[]" id="" value="0" required>
                </div>
                <div class="col my-3 d-flex text-nowrap align-items-center">
                    <label for="" class="form-label m-0 me-1">上架數量：</label>
                    <input type="number" class="form-control" name="p_sale[]" id="" value="0" required>
                </div>
            </div>
            <div class="copy">

            </div>
            <i class="btn btn-primary plus fa-solid fa-plus"> 添加尺寸</i>
            <i class="btn btn-danger minus fa-solid fa-minus"> 刪除尺寸</i>
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
            <div class="mb-2">
                <label for="" class="form-label">商品說明：</label>
                <textarea class="form-control" name="p_intro" rows="3" id="" required></textarea>
            </div>
            <button class="btn btn-primary" type="submit">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
            <?php if (isset($_SESSION["error"]["message"])): ?>
                <div class="rounded p-1 pb-2 text-white bg-danger">
                    <?= $_SESSION["error"]["message"] ?>
                </div>
            <?php
                unset($_SESSION["error"]["message"]);
            endif ?>
        </form>
    </div>
</body>
<script>
    const input_file = document.querySelector("input[type='file']");
    const content = document.querySelector(".content");
    const alert = document.querySelector(".text-danger");

    const plus = document.querySelector(".plus");
    const copy = document.querySelector(".copy");
    const minus = document.querySelector(".minus");
    let newform = [];


    plus.addEventListener("click", () => {
        if (newform.length < 4) {
            const copyfrom = document.querySelector(".form-size");
            const newCopy = copyfrom.cloneNode(true);
            copy.appendChild(newCopy);
            newCopy.classList.add('newform');
        }
        newform = document.querySelectorAll(".newform");
        console.log(newform);
    });


    minus.addEventListener("click", () => {
        newform = document.querySelectorAll(".newform");
        if (newform.length > 0) {
            newform[0].remove();
        }
    })

    input_file.addEventListener("change", e => {
        console.log(e.currentTarget.files.length); // 打印選擇的文件數量
        for (let i = 0; i < e.currentTarget.files.length; i++) {
            const file = e.currentTarget.files[i];
            if (file.type.startsWith("image/")) { // 確保選擇的是圖片
                alert.textContent = "";
                const node = document.createElement("img"); // 創建一個 <img> 元素
                const src = URL.createObjectURL(file); // 創建指向圖片的臨時 URL
                node.src = src; // 設定圖片的源
                content.append(node); // 把圖片添加到 <div class="content"> 中
                const img = document.querySelectorAll("img");
                img[i].addEventListener('click', () => {
                    img[i].remove(); // 從畫面上移除
                });
            } else {
                alert.textContent = "僅能上傳圖片檔案";
                input_file.value = null;
            }
        }
    });
</script>

</html>