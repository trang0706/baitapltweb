<?php
$fullname = $email = $topic = $message = '';
$errList = [];
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $topic    = trim($_POST['topic'] ?? 'Hỗ trợ kỹ thuật');
    $message  = trim($_POST['message'] ?? '');

    // 1. Kiểm tra Họ tên
    if (empty($fullname)) {
        $errList['fullname'] = 'Họ tên không được bỏ trống.';
    }

    // 2. Kiểm tra Email
    if (empty($email)) {
        $errList['email'] = 'Vui lòng nhập Email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errList['email'] = 'Định dạng Email chưa chính xác.';
    }

    // 3. Kiểm tra Nội dung
    $lenMsg = mb_strlen($message, 'UTF-8');
    if (empty($message)) {
        $errList['message'] = 'Nội dung liên hệ không được để trống.';
    } elseif ($lenMsg < 10 || $lenMsg > 500) {
        $errList['message'] = 'Nội dung phải chứa từ 10 đến 500 ký tự.';
    }

    // 4. Kiểm tra File Upload (3 điều kiện)
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['avatar'];

        // ĐK 1: Kiểm tra lỗi hệ thống
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errList['avatar'] = 'Tải file bị lỗi, hãy thử lại.';
        } else {
            // ĐK 2: Kích thước <= 2MB
            if ($file['size'] > 2 * 1024 * 1024) {
                $errList['avatar'] = 'Dung lượng ảnh phải nhỏ hơn 2MB.';
            }
            // ĐK 3: Đuôi file hợp lệ
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $validExts = ['png', 'jpg', 'jpeg', 'gif'];
            if (!in_array($ext, $validExts)) {
                $errList['avatar'] = 'Chỉ chấp nhận định dạng PNG, JPG, JPEG, GIF.';
            }
        }

        if (empty($errList)) {
            $dir = 'uploads/';
            if (!file_exists($dir)) mkdir($dir, 0777, true);
            move_uploaded_file($file['tmp_name'], $dir . time() . '_' . $file['name']);
        }
    } else {
        $errList['avatar'] = 'Yêu cầu chọn ảnh đại diện.';
    }

    if (empty($errList)) {
        $isSuccess = true;
        $fullname = $email = $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Liên Hệ Hệ Thống</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7fafc; padding: 20px; }
        .wrapper { max-width: 480px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-row { margin-bottom: 14px; }
        .form-row label { display: block; font-weight: bold; margin-bottom: 4px; font-size: 13px; }
        .form-row input, .form-row select, .form-row textarea { width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px; box-sizing: border-box; }
        .is-invalid { border-color: #e53e3e !important; }
        .msg-error { color: #e53e3e; font-size: 12px; margin-top: 3px; }
        .btn-send { width: 100%; padding: 10px; background: #2b6cb0; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        .success-box { background: #c6f6d5; color: #22543d; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

<div class="wrapper">
    <h2 style="text-align: center; color: #2d3748; margin-top: 0;">LIÊN HỆ</h2>

    <?php if ($isSuccess): ?>
        <div class="success-box">Gửi liên hệ thành công! Dữ liệu đã được ghi nhận.</div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <label>Họ tên:</label>
            <input type="text" name="fullname" class="<?= isset($errList['fullname']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($fullname) ?>">
            <?php if (isset($errList['fullname'])): ?><div class="msg-error"><?= $errList['fullname'] ?></div><?php endif; ?>
        </div>

        <div class="form-row">
            <label>Email:</label>
            <input type="text" name="email" class="<?= isset($errList['email']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($errList['email'])): ?><div class="msg-error"><?= $errList['email'] ?></div><?php endif; ?>
        </div>

        <div class="form-row">
            <label>Chủ đề:</label>
            <select name="topic">
                <option value="Hỗ trợ kỹ thuật" <?= $topic === 'Hỗ trợ kỹ thuật' ? 'selected' : '' ?>>Hỗ trợ kỹ thuật</option>
                <option value="Góp ý dịch vụ" <?= $topic === 'Góp ý dịch vụ' ? 'selected' : '' ?>>Góp ý dịch vụ</option>
            </select>
        </div>

        <div class="form-row">
            <label>Ảnh đại diện:</label>
            <input type="file" name="avatar" accept="image/*">
            <?php if (isset($errList['avatar'])): ?><div class="msg-error"><?= $errList['avatar'] ?></div><?php endif; ?>
        </div>

        <div class="form-row">
            <label>Nội dung:</label>
            <textarea name="message" rows="4" class="<?= isset($errList['message']) ? 'is-invalid' : '' ?>"><?= htmlspecialchars($message) ?></textarea>
            <?php if (isset($errList['message'])): ?>
                <div class="msg-error"><?= $errList['message'] ?></div>
            <?php else: ?>
                <span style="font-size: 11px; color: #a0aec0;">Độ dài nội dung từ 10 - 500 ký tự.</span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-send">Gửi liên hệ</button>
    </form>
</div>

</body>
</html>