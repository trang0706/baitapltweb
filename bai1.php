<?php
// Mảng danh sách sinh viên
$danhSachSV = [
    ['hoTen' => 'Nguyễn Văn A', 'diemGK' => 7.5, 'diemCK' => 8.0],
    ['hoTen' => 'Trần Thị B',  'diemGK' => 4.0, 'diemCK' => 5.0],
    ['hoTen' => 'Lê Văn C',    'diemGK' => 3.5, 'diemCK' => 4.0]
];

// Hàm tính điểm trung bình
function calculateAverage($gk, $ck) {
    $trungBinh = ($gk + $ck) / 2;
    return $trungBinh;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết Quả Học Tập Sinh Viên</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fafafa; }
        .tbl-scores { width: 75%; margin: 30px auto; border-collapse: collapse; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        .tbl-scores th, .tbl-scores td { border: 1px solid #dcdcdc; padding: 12px; text-align: center; }
        .tbl-scores th { background-color: #2b6cb0; color: #ffffff; text-transform: uppercase; font-size: 14px; }
        .tbl-scores tr:nth-child(2n) { background-color: #f7fafc; }
        .badge-pass { color: #276749; font-weight: 600; background-color: #c6f6d5; padding: 4px 8px; border-radius: 4px; }
        .badge-fail { color: #9b2c2c; font-weight: 600; background-color: #fed7d7; padding: 4px 8px; border-radius: 4px; }
    </style>
</head>
<body>

    <h2 style="text-align: center; color: #2d3748;">DANH SÁCH ĐIỂM THI SINH VIÊN</h2>

    <table class="tbl-scores">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ & Tên</th>
                <th>Điểm GK</th>
                <th>Điểm CK</th>
                <th>ĐTB</th>
                <th>Đánh giá</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for ($i = 0; $i < count($danhSachSV); $i++) {
                $sv = $danhSachSV[$i];
                $dtb = calculateAverage($sv['diemGK'], $sv['diemCK']);
                $isPass = ($dtb >= 5.0);
            ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($sv['hoTen'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= number_format($sv['diemGK'], 1) ?></td>
                    <td><?= number_format($sv['diemCK'], 1) ?></td>
                    <td><strong><?= number_format($dtb, 1) ?></strong></td>
                    <td>
                        <span class="<?= $isPass ? 'badge-pass' : 'badge-fail' ?>">
                            <?= $isPass ? 'Đạt' : 'Chưa đạt' ?>
                        </span>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>