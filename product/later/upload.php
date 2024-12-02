<!doctype html>
<html lang="en">

<head>
    <title>upload</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
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


        /* .content {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .image-wrapper {
            position: relative;
            display: inline-block;
        }

        .image-wrapper img {
            max-width: 150px;
            max-height: 150px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .image-wrapper .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
            width: 25px;
            height: 25px;
        } */
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

    // var img = content.querySelectorAll("img");
    // img[i].addEventListener("click", e => {
    //     console.log(i);
    //     console.log(files);
    //     // newFiles = files.splice(i, 1);
    //     // e.currentTarget.files[i].remove();
    //     // img[i].remove();
    //     console.log(newFiles);
    // })
</script>

</html>


<!-- input_file.addEventListener("change", e => {
        console.log(e.target.files);
        const files = Array.from(e.target.files);
        console.log(files);
        files.forEach(file => {
            upload.push(file);
            display(file);
        });
        input_file.value = '';
    });

    function display(file) {
        const reader = new FileReader();

        reader.onload = (e) => {
            // 創建圖片容器
            content.classList.add('image-wrapper');

            // 創建圖片元素
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = file.name;

            // 創建刪除按鈕
            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Delete';
            deleteButton.classList.add('delete-button');

            // 點擊刪除圖片
            deleteButton.addEventListener('click', () => {
                const index = upload.indexOf(file);
                if (index > -1) {
                    upload.splice(index, 1); // 從清單中移除
                }
                content.remove(); // 從畫面上移除
            });

            // 將圖片和按鈕添加到容器
            content.appendChild(img);
            content.appendChild(deleteButton);
        };

        reader.readAsDataURL(file);
    }

    // 攔截表單提交
    form.addEventListener('submit', (event) => {
        event.preventDefault(); // 阻止默認表單提交行為

        const formData = new FormData();

        // 將剩餘的檔案添加到 FormData
        upload.forEach(file => {
            formData.append('file', file);
        });

        // 使用 Fetch 提交
        fetch(uploadForm.action, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(result => {
                alert('Files uploaded successfully!');
                console.log(result);
            })
            .catch(error => {
                console.error('Error uploading files:', error);
            });
    }); -->