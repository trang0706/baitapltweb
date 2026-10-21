<?php
$hoTen = $diemCC = $diemGK = $diemCK = '';
$msgError = '';
$dtbMon = null;
$xepLoai = '';

function isValidScore($val) {
    if (!is_numeric($val)) return false;
    $num = (float)$val;
    return ($num >= 0 && $num <= 10);
}

function finalScore($cc, $gk, $ck) {
    return ($cc * 0.10) + ($gk * 0.30) + ($ck * 0.60);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoTen  = trim($_POST['hoTen'] ?? '');
    $diemCC = trim($_POST['diemCC'] ?? '');
    $diemGK = trim($_POST['diemGK'] ?? '');
    $diemCK = trim($_POST['diemCK'] ?? '');

    if ($hoTen === '') {
        $msgError = 'Vui lòng điền họ tên sinh viên.';
    } elseif (!isValidScore($diemCC) || !isValidScore($diemGK) || !isValidScore($diemCK)) {
        $msgError = 'Các điểm số phải là số nằm trong khoảng [0 - 10]!';
    } else {
        $dtbMon = finalScore((float)$diemCC, (float)$diemGK, (float)$diemCK);
        
        switch (true) {
            case ($dtbMon >= 8.5): $xepLoai = 'Giỏi'; break;
            case ($dtbMon >= 7.0): $xepLoai = 'Khá'; break;
            case ($dtbMon >= 5.0): $xepLoai = 'Trung bình'; break;
            default: $xepLoai = 'Chưa đạt'; break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tính Điểm Môn Học</title>
    <style>
        body { font-family: Roboto, sans-serif; background: #eef2f5; padding: 20px; }
        .card-form { max-width: 450px; margin: auto; background: #fff; padding: 20px 30px; border-radius: 6px; border-top: 4px solid #3182ce; }
        .field { margin-bottom: 12px; }
        .field label { display: block; margin-bottom: 4px; font-size: 14px; font-weight: 600; }
        .field input { width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px; box-sizing: border-box; }
        .btn-calc { width: 100%; padding: 10px; background: #3182ce; color: #fff; border: 0; border-radius: 4px; font-weight: bold; cursor: pointer; }
        .err-box { background: #fff5f5; color: #c53030; padding: 10px; border-radius: 4px; margin-bottom: 12px; font-size: 14px; border: 1px solid #feb2b2; }
        .res-box { margin-top: 15px; padding: 12px; background: #f0fff4; border: 1px solid #9ae6b4; border-radius: 4px; }
    </style>
</head>
<body>

<div class="card-form">
    <h3 style="text-align: center; margin-top: 0;">MÁY TÍNH ĐIỂM HỌC PHẦN</h3>

    <?php if ($msgError !== ''): ?>
        <div class="err-box"><?= htmlspecialchars($msgError) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="field">
            <label>Họ và tên:</label>
            <input type="text" name="hoTen" value="<?= htmlspecialchars($hoTen) ?>">
        </div>
        <div class="field">
            <label>Điểm CC (10%):</label>
            <input type="text" name="diemCC" value="<?= htmlspecialchars($diemCC) ?>">
        </div>
        <div class="field">
            <label>Điểm GK (30%):</label>
            <input type="text" name="diemGK" value="<?= htmlspecialchars($diemGK) ?>">
        </div>
        <div class="field">
            <label>Điểm CK (60%):</label>
            <input type="text" name="diemCK" value="<?= htmlspecialchars($diemCK) ?>">
        </div>
        <button type="submit" class="btn-calc">Tính Điểm</button>
    </form>

    <?php if ($dtbMon !== null && $msgError === ''): ?>
        <div class="res-box">
            <p style="margin: 4px 0;"><strong>Sinh viên:</strong> <?= htmlspecialchars($hoTen) ?></p>
            <p style="margin: 4px 0;"><strong>Điểm tổng kết:</strong> <?= number_format($dtbMon, 2) ?></p>
            <p style="margin: 4px 0;"><strong>Xếp loại:</strong> <?= $xepLoai ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>