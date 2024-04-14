<?php
session_start(); // Khởi động phiên làm việc

// Kiểm tra nếu người dùng đã đăng nhập, chuyển hướng đến trang home
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: home.php');
    exit();
}

// Kiểm tra khi người dùng nhấn nút đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kết nối đến cơ sở dữ liệu
    require_once "connect.php";

    // Lấy dữ liệu từ form đăng nhập
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Truy vấn kiểm tra thông tin đăng nhập
    $query = "SELECT * FROM quantrivien WHERE email = :email AND matKhau = :password";
    $statement = $pdo->prepare($query);
    $statement->execute([
        ':email' => $email,
        ':password' => $password
    ]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem người dùng có tồn tại hay không
    if ($user) {
        // Lưu thông tin người dùng vào phiên làm việc
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Chuyển hướng người dùng đến trang home sau khi đăng nhập thành công
        header('Location: home.php');
        exit();
    } else {
        // Đăng nhập không thành công, chuyển hướng người dùng đến trang đăng nhập với thông báo lỗi
        header('Location: login.php?error=invalid_credentials');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 uppercase">
                Login to your account
            </h2>
        </div>
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" action="login.php" method="post">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email address
                        </label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1 relative">
                            <input type="password" name="password" id="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember-me" id="remember-me" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                                Remember me
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="/forgot-password" class="font-medium text-blue-600 hover:text-blue-500">
                                Forgot your password?
                            </a>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="group relative w-full h-[40px] flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Submit
                        </button>
                    </div>
                    <div class="flex w-full">
                        <h4>Not have any account?</h4>
                        <a href="/sign-up.php" class="text-blue-600 pl-2">
                            Sign Up
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>