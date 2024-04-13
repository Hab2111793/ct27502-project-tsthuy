<?php
session_start(); // Khởi động session

require_once "connect.php";

// Thực hiện thêm sách
if (
    isset($_POST['maSach']) && isset($_POST['tenSach'])
    && isset($_POST['maTG']) && isset($_POST['maNXB']) && isset($_POST['maLoai'])
) {
    $maSach = $_POST['maSach'];
    $tenSach = $_POST['tenSach'];
    $maTG = $_POST['maTG'];
    $maNXB = $_POST['maNXB'];
    $maLoai = $_POST['maLoai'];
    $query = "INSERT INTO sach(maSach, tenSach, maTG, maNXB, maLoai)
                    VALUES (?, ?, ?, ?, ?)";
    $statement = $pdo->prepare($query);
    $statement->execute([$maSach, $tenSach, $maTG, $maNXB, $maLoai]);



    // Chuyển hướng về trang index.php sau khi thực hiện thêm sách
    header("Location: book_manage.php");
}
