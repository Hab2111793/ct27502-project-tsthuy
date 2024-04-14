<?php
function handle_avatar_upload(): string | bool
{
    if (!isset($_FILES['avatar'])) {
        return false;
    }
    $avatar = $_FILES['avatar'];
    $avatar_name = $avatar['name'];
    $avatar_tmp_name = $avatar['tmp_name'];
    $avatar_size = $avatar['size'];
    $avatar_error = $avatar['error'];

    if ($avatar_error !== 0 || $avatar_size > 10000000) {
        return false;
    }

    // Sử dụng đường dẫn tuyệt đối cho thư mục tải lên
    $uploads_directory = __DIR__ . '/../public/uploads/';
    $avatar_new_name = uniqid() . '_' . $avatar_name;
    $avatar_destination = $uploads_directory . $avatar_new_name;

    if (!move_uploaded_file($avatar_tmp_name, $avatar_destination)) {
        return false;
    }
    return $avatar_new_name;
}

function redirect(string $location): void
{
    header('Location: ' . $location, true, 302);
    exit();
}

function html_escape(string|null $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8', false);
}
