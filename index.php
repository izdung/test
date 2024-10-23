<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PHP Example</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        <a class="nav-link" href="connect.php">Connect MySQL</a>
                        <a class="nav-link" href="update.php">Update</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container my-3">
        <nav class="alert alert-primary" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </nav>

        <div>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "db_tran_ngoc_dung";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT id, title, description, ImageUrl FROM course";
            $result = $conn->query($sql);

            if (!$result) {
                die("Query failed: " . $conn->error);
            }
            ?>

            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
            <div class="col">
                <div class="card">
                    <img src="images/' . $row["ImageUrl"] . '" class="card-img-top" alt="' . $row["title"] . '">
                    <div class="card-body">
                        <h5 class="card-title">' . $row["title"] . '</h5>
                        <p class="card-text">' . $row["description"] . '</p>
                    </div>
                </div>
            </div>
            ';
                    }
                } else {
                    echo "No courses found.";
                }

                // Đóng kết nối
                $conn->close();
                ?>
            </div>

            <?php
            session_start();

            // Kiểm tra nếu form đã được submit
            if (isset($_POST['submit'])) {
                $filename = 'text'; // Tên file cố định là "text" (có thể thay đổi theo ý bạn)
                $content = $_POST['content']; // Lấy nội dung từ input

                // Đường dẫn đến file
                $directory = 'uploads/'; // Thay đổi đường dẫn tới thư mục mong muốn
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true); // Tạo thư mục nếu chưa tồn tại
                }

                $filePath = $directory . $filename . '.txt'; // Đường dẫn lưu file

                // Ghi nội dung vào file
                if (file_put_contents($filePath, $content) !== false) {
                    echo '<div class="alert alert-success" role="alert">File "' . htmlspecialchars($filename) . '.txt" đã được ghi thành công.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Có lỗi khi ghi file. Vui lòng thử lại.</div>';
                }
            }
            ?>
            <hr>
            <form class="row" method="POST" enctype="multipart/form-data">
                <div class="col">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="content" placeholder="Nội dung" name="content" required>
                        <label for="content">Nội dung ghi vào file</label>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Write file</button>
                </div>
                <div class="col">
                </div>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    </body>

</html>
