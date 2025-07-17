<?php
include '../config/db.php';
include '../config/session_check.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $today = date('Y-m-d');

    // আজকের মোট আয়
    $stmt = $pdo->prepare("SELECT SUM(amount) AS total_income FROM accounting_entries WHERE type = 'Income' AND entry_date = ?");
    $stmt->execute([$today]);
    $income = $stmt->fetch()['total_income'] ?? 0;

    // আজকের মোট ব্যয়
    $stmt = $pdo->prepare("SELECT SUM(amount) AS total_expense FROM accounting_entries WHERE type = 'Expense' AND entry_date = ?");
    $stmt->execute([$today]);
    $expense = $stmt->fetch()['total_expense'] ?? 0;

    // আজকের লেনদেন লিস্ট
    $stmt = $pdo->prepare("SELECT * FROM accounting_entries WHERE entry_date = ? ORDER BY id DESC");
    $stmt->execute([$today]);
    $today_records = $stmt->fetchAll();

} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>অ্যাকাউন্টিং ড্যাশবোর্ড</title>
    <link href="../assets/bootstrap.min.css" rel="stylesheet">
    <style>
        .card { border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .table thead { background-color: #f8f9fa; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">💼 আজকের অ্যাকাউন্টিং ড্যাশবোর্ড (<?= $today ?>)</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">আজকের মোট আয়</h5>
                    <p class="card-text fs-4">৳ <?= number_format($income, 2) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">আজকের মোট ব্যয়</h5>
                    <p class="card-text fs-4">৳ <?= number_format($expense, 2) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">আজকের ব্যালেন্স</h5>
                    <p class="card-text fs-4">৳ <?= number_format($income - $expense, 2) ?></p>
                </div>
            </div>
        </div>
    </div>

    <h4>📋 আজকের লেনদেন</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>প্রকার</th>
                <th>পরিমাণ</th>
                <th>বিস্তারিত</th>
                <th>তারিখ</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($today_records): ?>
            <?php foreach($today_records as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= ($row['type'] == 'Income') ? 'আয়' : 'ব্যয়' ?></td>
                <td>৳ <?= number_format($row['amount'], 2) ?></td>
                <td><?= htmlspecialchars($row['details']) ?></td>
                <td><?= $row['entry_date'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center text-danger">⚠️ আজ কোনো লেনদেন নেই।</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
