<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course</title>
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
                        <a class="nav-link" href="index.php">Home</a>
                        <a class="nav-link" href="connect.php">Connect MySQL</a>
                        <a class="nav-link active" href="update_course.php">Update</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container my-3">
        <nav class="alert alert-primary" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Course</li>
            </ol>
        </nav>

        <h2>Danh sách các khóa học</h2>
        <ul class="list-group mb-3">
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "db_tran_ngoc_dung";

                // Kết nối đến cơ sở dữ liệu
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Lấy danh sách khóa học
                $sql = "SELECT id, title FROM course";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Hiển thị danh sách khóa học
                    while($row = $result->fetch_assoc()) {
                        echo '<li class="list-group-item">
                                <a href="update.php?course_id=' . $row["id"] . '">' . htmlspecialchars($row["title"]) . '</a>
                              </li>';
                    }
                } else {
                    echo "<li class='list-group-item'>Không có khóa học nào.</li>";
                }
            ?>
        </ul>

        <?php
            // Nếu có course_id được truyền đến, hiển thị form cập nhật
            if (isset($_GET['course_id'])) {
                $course_id = intval($_GET['course_id']); // Sử dụng intval để đảm bảo course_id là số

                // Kết nối lại đến cơ sở dữ liệu để lấy thông tin khóa học
                $sql = "SELECT id, title, description FROM course WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $course_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $course = $result->fetch_assoc();
                } else {
                    echo "<div class='alert alert-danger'>Khóa học không tồn tại.</div>";
                }

                // Xử lý cập nhật khóa học
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_course'])) {
                    $new_title = $_POST['new_title'];
                    $new_description = $_POST['new_description'];

                    // Cập nhật thông tin khóa học
                    $update_sql = "UPDATE course SET title=?, description=? WHERE id=?";
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("ssi", $new_title, $new_description, $course_id);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Khóa học đã được cập nhật thành công!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Có lỗi xảy ra khi cập nhật khóa học: " . $conn->error . "</div>";
                    }

                    $stmt->close();
                }
            } else {
                echo "<div class='alert alert-warning'>Không có khóa học nào được chọn để cập nhật.</div>";
            }
        ?>

        <?php if (isset($course)): ?>
            <h2>Cập nhật khóa học: <?php echo htmlspecialchars($course['title']); ?></h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="new_title" class="form-label">Tiêu đề mới</label>
                    <input type="text" class="form-control" id="new_title" name="new_title" value="<?php echo htmlspecialchars($course['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="new_description" class="form-label">Mô tả mới</label>
                    <textarea class="form-control" id="new_description" name="new_description" required><?php echo htmlspecialchars($course['description']); ?></textarea>
                </div>
                <button type="submit" name="update_course" class="btn btn-primary">Cập nhật khóa học</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>

