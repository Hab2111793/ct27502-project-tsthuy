<?php
require_once "connect.php";
session_start();
// Kiểm tra xem maSach đã được gửi qua biểu mẫu hay chưa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['maSach']) && isset($_POST['maNXB'])) {
    $maSach = $_POST['maSach'];
    $maNXB = $_POST['maNXB'];
    // Tiếp tục xử lý biến maSach ở đây
    if (
        isset($_POST['maSach']) && isset($_POST['maBanSao'])
        && isset($_POST['namXB']) && isset($_POST['ttMuon']) && isset($_POST['maNXB'])
    ) {
        // Lấy thông tin từ biểu mẫu
        $maSach = $_POST['maSach'];
        $maBanSao = $_POST['maBanSao'];
        $namXB = $_POST['namXB'];
        $ttMuon = $_POST['ttMuon'];
        $maNXB = $_POST['maNXB'];


        $query = "INSERT INTO bansaosach (maSach, maBanSao, namXB, ttMuon, maNXB) 
                VALUES (?, ?, ?, ?, ?)";
        $statement = $pdo->prepare($query);
        $statement->execute([
            $maSach, $maBanSao, $namXB, $ttMuon, $maNXB
        ]);


        $redirectURL = 'book_copy.php';

        header("Location: $redirectURL");
    }
}
