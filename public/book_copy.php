    <?php

    require_once "connect.php";


    session_start();



    $searchResultsBooks = [];
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $query = 'SELECT maSach,tenSach,maNXB,maTG,maLoai FROM sach WHERE maSach LIKE :search OR tenSach LIKE :search 
            OR maLoai LIKE :search OR maNXB LIKE :search';
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':search' => '%' . $search . '%'
        ]);
        $searchResultsBooks =  $statement->fetchAll();
    } else {
        $query = "SELECT * FROM library_management.sach;";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $searchResultsBooks  = $statement->fetchAll();
    }

    // Xử lý tìm kiếm bản sao của sách
    $searchResultsCopies = [];
    $maSach = isset($_GET['maSach']) ? $_GET['maSach'] : '';
    $maNXB = isset($_GET['maNXB']) ? $_GET['maNXB'] : '';

    if (!empty($maSach)) {
        $query = 'SELECT * FROM bansaosach WHERE maSach = :maSach';
        $statement = $pdo->prepare($query);
        $statement->execute([':maSach' => $maSach]);
        $searchResultsCopies = $statement->fetchAll();
    }

    if (!empty($maNXB)) {
        $maNXB = $_GET['maNXB'];
    }

    $searchResults = [];
    if (isset($_GET['searchcopy'])) {
        $searchcopy = $_GET['searchcopy'];
        $query = 'SELECT maSach, maBanSao, namXB, ttMuon, maNXB FROM bansaosach WHERE maSach LIKE :searchcopy 
        OR maBanSao LIKE :searchcopy OR maNXB LIKE :searchcopy';
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':searchcopy' => '%' . $searchcopy . '%'
        ]);
        $searchResults = $statement->fetchAll();
    } else {
    }




    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <title>Quản lý bản sao sách</title>
    </head>

    <body>

        <header class="bg-blue-700 text-white p-4 uppercase ">
            <div class="container mx-auto flex justify-between items-center px-4">
                <a href="home.php" class="text-2xl font-bold">Hệ thống quản lý thư viện</a>



                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="index.php" class="hover:text-gray-300">Độc giả</a></li>
                        <li><a href="thethuvien.php" class="hover:text-gray-300">Thẻ thư viện</a></li>
                        <li><a href="book_manage.php" class="hover:text-gray-300">Quản lý sách</a></li>
                        <li><a href="return.php" class="hover:text-gray-300">Trả sách</a></li>
                        <li><a href="lichsumuontra.php" class="hover:text-gray-300">Lịch sử mượn trả</a></li>
                        <li><a href="login.php" class="hover:text-gray-300">
                                <?php
                                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                                    echo '<a href="Logout.php">Log out</a>';
                                } else {
                                    echo '<a href="login.php">Đăng Nhập</a>';
                                }
                                ?>
                            </a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <div class="container max-w-full mx-auto bg-white p-8 mt-8 rounded-lg shadow-lg">

            <h1 class="text-2xl font-bold mb-4 text-center uppercase ">Quản lý bản sao sách</h1>

            <!-- Hiển thị thông báo kết quả -->
            <?php if (isset($_SESSION['add_copy_result'])) : ?>
                <p><?php echo $_SESSION['add_copy_result']; ?></p>
                <?php unset($_SESSION['add_copy_result']); ?>
            <?php endif; ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Form tìm kiếm sách -->
                <div>
                    <h2 class="text-xl font-bold">Tìm kiếm sách</h2>
                    <form action="book_copy.php" method="GET" class="mb-4">
                        <input type="text" name="search" placeholder="Tìm kiếm sách" class="p-2 border rounded-md mr-2 text-black">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Tìm kiếm</button>
                    </form>

                    <!-- Danh sách kết quả tìm kiếm sách -->

                    <?php if (!empty($searchResultsBooks)) : ?>
                        <h2 class="text-xl font-bold">Kết quả tìm kiếm sách</h2>
                        <table class="mt-4">
                            <tr>
                                <th class="px-4 py-2">Mã Sách</th>
                                <th class="px-4 py-2">Tên Sách</th>
                                <th class="px-4 py-2">Thao Tác</th>
                            </tr>
                            <?php foreach ($searchResultsBooks as $book) : ?>
                                <tr>
                                    <td class="border px-4 py-2"><?php echo $book['maSach']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $book['tenSach']; ?></td>
                                    <td class="border px-4 py-2">
                                        <form action="book_copy.php" method="GET" class="book-action">
                                            <input type="hidden" name="maSach" value="<?php echo $book['maSach']; ?>">
                                            <input type="hidden" name="maNXB" value="<?php echo $book['maNXB']; ?>">
                                            <input type="hidden" name="searchcopy" value="<?php echo $book['maSach'] ?>">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Xem bản sao</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
                <div class="">
                    <!-- right -->
                    <?php if (!empty($maSach)) : ?>
                        <!-- tim kiem ban sao -->
                        <?php if (!empty($searchResultsCopies)) : ?>
                            <!-- Form tìm kiếm bản sao của sách -->
                            <h2 class="text-xl font-bold mt-8">Tìm kiếm bản sao sách</h2>
                            <form action="book_copy.php" method="GET" class="mb-4">
                                <input type="hidden" name="maSach" value="<?php echo $maSach; ?>">
                                <input type="text" name="searchcopy" placeholder="Tìm kiếm bản sao của sách" class="p-2 border rounded-md mr-2 text-black">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Tìm kiếm</button>
                            </form>

                            <h2 class="text-xl font-bold">Danh sách bản sao của sách</h2>
                            <table class="mt-4">
                                <tr>
                                    <th class="px-4 py-2">Mã Bản Sao</th>
                                    <th class="px-4 py-2">Năm Xuất Bản</th>
                                    <th class="px-4 py-2">Tình Trạng Mượn</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                                <?php foreach ($searchResultsCopies as $copy) : ?>
                                    <tr>
                                        <td class="border px-4 py-2"><?php echo $copy['maBanSao']; ?></td>
                                        <td class="border px-4 py-2"><?php echo $copy['namXB']; ?></td>
                                        <td class="border px-4 py-2"><?php echo $copy['ttMuon']; ?></td>
                                        <td class="border px-4 py-2">
                                            <a href="./edit_book_copy.php?maBanSao=<?php echo $copy['maBanSao']; ?>&maSach=<?php echo $maSach ?>" class="text-blue-500 hover:text-blue-700">Edit</a> |
                                            <a href="./delete_copy_book.php?maBanSao=<?php echo $copy['maBanSao'] ?>&maSach=<?php echo $maSach ?>&maNXB=<?php echo $maNXB ?>&searchcopy=<?php echo $_GET['searchcopy'] ?>" onclick="return confirm('Bạn có muốn xóa bản sao?')" class="text-red-500 hover:text-red-700">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($searchResultsCopies)) : ?>
                                    <tr>
                                        <td colspan="4" class="border px-4 py-2">Chưa có bản sao.</td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        <?php endif; ?>

                        <!-- Form thêm bản sao mới -->
                        <?php if (!empty($maSach) || !empty($searchResultsCopies)) : ?>
                            <h2 class="text-xl font-bold mt-8">Thêm bản sao mới</h2>
                            <form action="./add_book_copy.php" method="POST" class="mb-4">
                                <input type="text" hidden name='searchcopy' value="<?php echo htmlspecialchars($searchcopy); ?>">
                                <input type="text" name="maSach" value="<?php echo htmlspecialchars($_GET['maSach'] ?? ''); ?>" class="p-2 border rounded-md mr-2 text-black">
                                <input type="text" name="maBanSao" placeholder="Mã bản sao" class="p-2 border rounded-md mr-2 text-black">
                                <input type="text" name="namXB" placeholder="Năm xuất bản" class="p-2 border rounded-md mr-2 text-black">
                                <input type="text" name="ttMuon" placeholder="Tình trạng mượn" class="p-2 border rounded-md mr-2 text-black">
                                <input type="text" name="maNXB" value="<?php echo htmlspecialchars($_GET['maNXB'] ?? ''); ?>" class="p-2 border rounded-md mr-2 text-black">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Thêm bản sao</button>

                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

    </body>

    </html>