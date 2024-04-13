<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/classes/Contact.php';

use CT275\Labs\Contact;

$contact = new Contact($PDO);
$id = isset($_REQUEST['id']) ?
    filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT) : -1;
if ($id < 0 || !($contact->find($id))) {
    redirect('/');
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý tải lên avatar và lưu đường dẫn vào biến $avatar_file
    $avatar_file = handle_avatar_upload();

    // Kiểm tra xem kết quả tải lên thành công hay không
    if ($avatar_file === false) {
        // Xử lý lỗi tải lên ảnh
        // ...
    } else {
        // Tải lên thành công, tiếp tục xử lý biểu mẫu
        // Gán đường dẫn avatar vào thuộc tính $avatar của đối tượng Contact
        $_POST['avatar'] = $avatar_file;
    }

    if ($contact->update($_POST)) {
        // Cập nhật dữ liệu thành công
        redirect('/');
    }
    // Cập nhật dữ liệu không thành công
    $errors = $contact->getValidationErrors();
}

include_once __DIR__ . '/../src/partials/header.php';
?>

<body>
    <?php include_once __DIR__ . '/../src/partials/navbar.php' ?>

    <!-- Main Page Content -->
    <div class="container">

        <?php
        $subtitle = 'Update your contacts here.';
        include_once __DIR__ . '/../src/partials/heading.php';
        ?>

        <div class="row">
            <div class="col-12">

                <form method="post" class="col-md-6 offset-md-3" enctype="multipart/form-data">

                    <input type="hidden" name="id" value="<?= $contact->getId() ?>">
                    <!-- Avatar -->
                    <!-- <div class="form-group">
                        <label for="avatar">Choose Avatar</label>
                        <input type="file" name="avatar" class="form-control-file" id="avatar" />
                        <?php if (!empty($contact->avatar)) : ?>
                            <div class="current-avatar">
                                <label>Current Avatar:</label><br>
                                <img src="<?= '/uploads/' . html_escape($contact->avatar) ?>" alt="Current Avatar" style="max-width: 200px; max-height: 200px; margin-top: 10px;">
                            </div>
                        <?php endif; ?>
                    </div> -->
                    <!-- Avatar -->
                    <div class="form-group">
                        <label for="avatar">Choose Avatar</label>
                        <input type="file" name="avatar" class="form-control-file" id="avatar" onchange="previewAvatar(event)" />
                        <div class="current-avatar">
                            <label>Current Avatar:</label><br>
                            <img id="current-avatar-img" src="<?= '/uploads/' . html_escape($contact->avatar) ?>" alt="Current Avatar" style="max-width: 200px; max-height: 200px; margin-top: 10px;">
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>" maxlen="255" id="name" placeholder="Enter Name" value="<?= html_escape($contact->name) ?>" />

                        <?php if (isset($errors['name'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['name'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" class="form-control<?= isset($errors['phone']) ? ' is-invalid' : '' ?>" maxlen="255" id="phone" placeholder="Enter Phone" value="<?= html_escape($contact->phone) ?>" />

                        <?php if (isset($errors['phone'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['phone'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label for="notes">Notes </label>
                        <textarea name="notes" id="notes" class="form-control<?= isset($errors['notes']) ? ' is-invalid' : '' ?>" placeholder="Enter notes (maximum character limit: 255)"><?= html_escape($contact->notes) ?></textarea>

                        <?php if (isset($errors['notes'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['notes'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>

                    <!-- Submit -->
                    <button type="submit" name="submit" class="btn btn-primary">Update Contact</button>
                </form>

            </div>
        </div>

    </div>

    <?php include_once __DIR__ . '/../src/partials/footer.php' ?>
    <script>
        function previewAvatar(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('current-avatar-img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>

</html>