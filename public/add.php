<?php
require_once __DIR__ . '/../src/bootstrap.php';

use CT275\Labs\Contact;

$errors = [];

// Xử lý khi người dùng gửi biểu mẫu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý tải lên avatar và lưu trữ đường dẫn vào biến $avatar_file
    $avatar_file = handle_avatar_upload();

    // Kiểm tra xem kết quả tải lên thành công hay không
    if ($avatar_file === false) {
        // Xử lý lỗi tải lên ảnh
        // ...
    } else {
        // Tải lên thành công, tiếp tục xử lý biểu mẫu
        $contact = new Contact($PDO);

        // Điền thông tin từ biểu mẫu vào đối tượng Contact
        $contact->fill($_POST);

        // Gán đường dẫn avatar vào thuộc tính $avatar của đối tượng Contact
        $contact->avatar = $avatar_file;

        // Validate và lưu đối tượng Contact vào cơ sở dữ liệu
        if ($contact->validate()) {
            $contact->save() && redirect('/');
        }

        // Lấy danh sách lỗi nếu có
        $errors = $contact->getValidationErrors();
    }
}

include_once __DIR__ . '/../src/partials/header.php';
?>


<body>
    <?php include_once __DIR__ . '/../src/partials/navbar.php' ?>

    <!-- Main Page Content -->
    <div class="container">

        <?php
        $subtitle = 'Add your contacts here.';
        include_once __DIR__ . '/../src/partials/heading.php';
        ?>

        <div class="row">
            <div class="col-12">

                <form method="post" class="col-md-6 offset-md-3" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="avatar">Choose Avatar</label>
                        <input type="file" name="avatar" class="form-control-file" id="avatar" onchange="previewImage(event)" />
                        <img id="avatar-preview" src="#" alt="Avatar Preview" style="display: none; max-width: 200px; max-height: 200px; margin-top: 10px;" />
                    </div>

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>" maxlen="255" id="name" placeholder="Enter Name" value="<?= isset($_POST['name']) ? html_escape($_POST['name']) : '' ?>" />

                        <?php if (isset($errors['name'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['name'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" class="form-control<?= isset($errors['phone']) ? ' is-invalid' : '' ?>" maxlen="255" id="phone" placeholder="Enter Phone" value="<?= isset($_POST['phone']) ? html_escape($_POST['phone']) : '' ?>" />

                        <?php if (isset($errors['phone'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['phone'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label for="notes">Notes </label>
                        <textarea name="notes" id="notes" class="form-control<?= isset($errors['notes']) ? ' is-invalid' : '' ?>" placeholder="Enter notes (maximum character limit: 255)"><?= isset($_POST['notes']) ? html_escape($_POST['notes']) : '' ?></textarea>

                        <?php if (isset($errors['notes'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['notes'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>

                    <!-- Submit -->
                    <button type="submit" name="submit" class="btn btn-primary">Add Contact</button>
                </form>

            </div>
        </div>

    </div>

    <?php include_once __DIR__ . '/../src/partials/footer.php' ?>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('avatar-preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>


</html>