<!doctype html>
<html lang="en">

<head>
    <title>upload</title>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
    <style>
        .content.overflow-hidden {
            img {
                object-fit: cover;
                width: 200px;
                height: 200px;
                margin: 5px;
                border: solid 1px #000;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-3">
        <form action="../DO/doUpload.php" method="post" enctype="multipart/form-data">
            <div class="mb-2">
                <label for="" class="form-label">名稱</label>
                <input type="text" class="form-control" name="name">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">選擇檔案</label>
                <input class="form-control" type="file" name="myFile[]" accept="image/*" multiple>
            </div>
            <hr>
            <h2>圖片預覽</h2>
            <div class="hstack gap-1 justify-content-start">
                <div class="content overflow-hidden">
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" type="submit">上傳</button>
            </div>
        </form>
    </div>
</body>
<script>
    const input_file = document.querySelector("input[type='file']");
    const content = document.querySelector(".content");
    const form = document.querySelector("form");
    let upload = [];


    input_file.addEventListener("change", e => {
        console.log(e.currentTarget.files.length); // 打印選擇的文件數量
        for (let i = 0; i < e.currentTarget.files.length; i++) {
            const file = e.currentTarget.files[i];
            if (file.type.startsWith("image/")) { // 確保選擇的是圖片
                const node = document.createElement("img"); // 創建一個 <img> 元素
                const src = URL.createObjectURL(file); // 創建指向圖片的臨時 URL
                node.src = src; // 設定圖片的源
                content.append(node); // 把圖片添加到 <div class="content"> 中
                const img = document.querySelectorAll("img");
                img[i].addEventListener('click', () => {
                    img[i].remove(); // 從畫面上移除
                });
            } else {
                console.log(`File ${file.name} is not an image.`);
            }
        }
    });
</script>

</html>