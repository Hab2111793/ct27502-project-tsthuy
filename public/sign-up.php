<?php
session_start(); // Khởi động session
require_once "connect.php"; // Kết nối đến cơ sở dữ liệu

// Xử lý khi người dùng nhấn nút "Đăng ký"
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Kiểm tra email đã tồn tại hay chưa
        $query = "SELECT * FROM quantrivien WHERE email = :email";
        $statement = $pdo->prepare($query);
        $statement->execute([':email' => $email]);
        $existingUser = $statement->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            // Email đã tồn tại, thông báo lỗi hoặc redirect đến trang đăng ký với thông báo lỗi
            header('Location: signup.php?error=email_taken');
            exit();
        } else {
            // Thêm người dùng mới vào cơ sở dữ liệu
            $query = "INSERT INTO quantrivien (email, tenQTV, matKhau) VALUES (:email, :name, :password)";
            $statement = $pdo->prepare($query);
            $statement->execute([
                ':email' => $email,
                ':name' => $name,
                ':password' => $password
            ]);

            // Đăng ký thành công, thực hiện các hành động cần thiết (ví dụ: redirect đến trang đăng nhập với thông báo thành công)
            header('Location: login.php?signup_success=true');
            exit();
        }
    } catch (PDOException $e) {
        if ($e->getCode() === '45000') {
            // Lỗi từ trigger, hiển thị thông báo lỗi
            $error_message = $e->getMessage();
            header('Location: signup.php?error=' . urlencode($error_message));
            echo "Error: " . $error_message;
            // exit();
        } else {
            // Xử lý các lỗi khác nếu cần
            echo "Error: " . $e->getMessage();
        }
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
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Register as a new user
            </h2>
        </div>
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" action="sign-up.php" method="POST">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Full Name
                        </label>
                        <div class="mt-1">
                            <input type="text" name="name" autocomplete="name" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email address
                        </label>
                        <div class="mt-1">
                            <input type="email" name="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1 relative">
                            <input type="<?php echo $visible ? 'text' : 'password'; ?>" name="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700"></label>

                    </div>

                    <div>
                        <button name="signup" type="submit" class="group relative w-full h-[40px] flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Submit
                        </button>
                    </div>
                    <div class="flex w-full">
                        <h4>Already have an account?</h4>
                        <a href="/login.php" class="text-blue-600 pl-2">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>