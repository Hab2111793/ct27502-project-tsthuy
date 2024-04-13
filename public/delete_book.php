<?php
session_start(); // Khởi động session

require_once "connect.php";


// Kiểm tra xác nhận xóa sách
if (isset($_GET['maSach'])) {
    // Lấy mã sách từ URL
    $maSach = $_GET['maSach'];

    $query = "DELETE FROM sach WHERE maSach = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$maSach]);
    // Chuyển hướng về trang index.php
    header("Location: book_manage.php");
    exit();
} else {
    // Nếu không có mã sách được cung cấp, chuyển hướng người dùng về trang index.php
    header("Location: book_manage.php");
    exit();
}
