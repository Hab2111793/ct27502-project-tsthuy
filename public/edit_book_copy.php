<?php

require_once "connect.php";

session_start();



// Xử lý khi người dùng nhấn nút "Update"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maBanSao = ($_POST['maBanSao']);
    $namXB = ($_POST['namXB']);
    $ttMuon = ($_POST['ttMuon']);
    $maSach = ($_POST['maSach']);
    $maNXB = ($_POST['maNXB']);

    $query = "UPDATE bansaosach SET
            namXB = ?,
            ttMuon = ?
            WHERE maBanSao = ?  and maSach = ?";
    $statement = $pdo->prepare($query);

    $statement->execute([
        $namXB, $ttMuon, $maBanSao, $maSach
    ]);

    header("Location: ./book_copy.php");
    exit();
}

// Lấy thông tin của bản sao sách từ cơ sở dữ liệu
if (isset($_GET['maBanSao'])) {
    $maBanSao = $_GET['maBanSao'];
    $maSach = $_GET['maSach'];
    $query = "SELECT * FROM bansaosach WHERE maBanSao = ? and maSach = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$maBanSao, $maSach]);
    $copyInfo = $statement->fetch();

    if (!$copyInfo) {
        echo "Không tìm thấy thông tin bản sao sách.";
        exit();
    }
} else {
    header("Location: ./book_copy.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style_copy.css">
    <title>Edit Copy</title>
</head>

<body>

    <div class="container">
        <h1>Edit Copy</h1>

        <?php if (isset($_SESSION['update_copy_result'])) : ?>
            <p><?php echo $_SESSION['update_copy_result']; ?></p>
            <?php unset($_SESSION['update_copy_result']); ?>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" name="maBanSao" value="<?php echo $copyInfo['maBanSao']; ?>">
            <label for="maSach">Mã Sách:</label>
            <input type="text" id="maSach" name="maSach" value="<?php echo $copyInfo['maSach']; ?>"><br>
            <label for="maNXB">Mã NXB:</label>
            <input type="text" id="maNXB" name="maNXB" value="<?php echo $copyInfo['maNXB']; ?>"><br>
            <label for="namXB">Năm Xuất Bản:</label>
            <input type="text" id="namXB" name="namXB" value="<?php echo $copyInfo['namXB']; ?>"><br>
            <label for="ttMuon">Tình Trạng Mượn:</label>
            <input type="text" id="ttMuon" name="ttMuon" value="<?php echo $copyInfo['ttMuon']; ?>"><br>
            <button type="submit">Update</button>
        </form>
    </div>

</body>

</html>