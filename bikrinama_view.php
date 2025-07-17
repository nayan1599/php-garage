<?php
include 'config/db.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query("SELECT * FROM bikrinama ORDER BY id DESC");
    $bikrinamas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("ডাটাবেজ সংযোগে সমস্যা: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8" />
    <title>বিক্রয় দলিল তালিকা</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4 text-center">বিক্রয় দলিলের তালিকা</h2>
    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>আইডি</th>
                <th>বিক্রেতা নাম</th>
                <th>ক্রেতা নাম</th>
                <th>গাড়ির রেজি নং</th>
                <th>গাড়ির চেসিস নং</th>
                <th>বিক্রয় তারিখ</th>
                <th>কর্ম</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($bikrinamas) > 0): ?>
            <?php foreach ($bikrinamas as $row): ?>
                <tr>
                    <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['seller_name']) ?></td>
                    <td><?= htmlspecialchars($row['buyer_name']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['reg_no']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['chassis_no']) ?></td>
                    <td class="text-center"><?= isset($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) : '-' ?></td>
                    <td class="text-center">
                        <a href="bikrinama_view_single.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center">কোনো দলিল পাওয়া যায়নি।</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
