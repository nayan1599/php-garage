<?php
// DB সংযোগ
include '../config/db.php';
include '../config/session_check.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// পেমেন্ট আইডি যাচাই
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("⚠️ ভুল অনুরোধ। পেমেন্ট আইডি লাগবে।");
}

$payment_id = (int)$_GET['id'];

// মূল পেমেন্ট ডেটা লোড
$stmt = $pdo->prepare("
    SELECT p.*, r.rental_id, c.customer_id, c.first_name, c.last_name, c.phone, g.gari_nam 
    FROM rental_payments p
    JOIN rentals r ON p.rental_id = r.rental_id
    JOIN customer c ON r.customer_id = c.customer_id
    JOIN gari g ON r.gari_id = g.gari_id
    WHERE p.payment_id = ?
");
$stmt->execute([$payment_id]);
$payment = $stmt->fetch();

if (!$payment) {
    die("⚠️ কোনো পেমেন্ট তথ্য পাওয়া যায়নি।");
}

$customer_id = $payment['customer_id'];

// কাস্টমারের রেন্টাল হিস্ট্রি
$stmt2 = $pdo->prepare("
    SELECT 
        g.gari_nam,
        COUNT(r.rental_id) AS rental_count
    FROM rental_payments p
    JOIN rentals r ON p.rental_id = r.rental_id
    JOIN customer c ON r.customer_id = c.customer_id
    JOIN gari g ON r.gari_id = g.gari_id
    WHERE r.customer_id = ?
    GROUP BY g.gari_id
 
");
$stmt2->execute([$customer_id]);
$rental_history = $stmt2->fetchAll();


 
// ভাড়া নেওয়ার সংখ্যা? // এখানে আমরা কাস্টমারের ভাড়া নেওয়ার সংখ্যা বের করছি
// কাস্টমারের মোট পেমেন্ট সারসংক্ষেপ




 
 


// কাস্টমারের মোট পরিশোধ ও মোট বাকি
$stmt3 = $pdo->prepare("
    SELECT 
        SUM(p.amount_paid + p.late_fee) AS total_paid,
        SUM(p.amount_due) AS total_due
    FROM rental_payments p
    WHERE p.customer_id = ?
");
$stmt3->execute([$customer_id]);
$summary = $stmt3->fetch();
$total_paid = $summary['total_paid'] ?? 0;
$total_due = $summary['total_due'] ?? 0;
$balance_due = $total_due - $total_paid;
?>


<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>👁️ কাস্টমার পেমেন্ট ও রেন্টাল স্টোরি</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>👁️ কাস্টমার পেমেন্ট ও রেন্টাল স্টোরি</h2>

    <table class="table table-bordered mb-4">
        <tr><th>কাস্টমার</th><td><?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?> (<?= htmlspecialchars($payment['phone']) ?>)</td></tr>
        <tr><th>গাড়ি (সর্বশেষ পেমেন্ট)</th><td><?= htmlspecialchars($payment['gari_nam']) ?></td></tr>
        <tr><th>পেমেন্ট তারিখ</th><td><?= htmlspecialchars($payment['payment_date']) ?></td></tr>
        <tr><th>পরিমাণ</th><td><?= number_format($payment['amount_paid'] + $payment['late_fee'], 2) ?> ৳</td></tr>
        <tr><th>স্ট্যাটাস</th><td><?= htmlspecialchars($payment['status']) ?></td></tr>
    </table>

    <h4>🚗 কাস্টমার রেন্টাল হিস্ট্রি</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>গাড়ি</th>
                <th>ভাড়া নেওয়ার সংখ্যা</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($rental_history): foreach ($rental_history as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['gari_nam']) ?></td>
                <td><?= $r['rental_count'] ?></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="2" class="text-center text-muted">⚠️ কোনো রেন্টাল হিস্ট্রি নেই।</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <h4>💰 পেমেন্ট সারসংক্ষেপ</h4>
    <table class="table table-bordered">
        <tr><th>মোট পরিশোধ</th><td><?= number_format($total_paid, 2) ?> ৳</td></tr>
        <tr><th>মোট পাওনা</th><td><?= number_format($total_due, 2) ?> ৳</td></tr>
        <tr><th>বাকি</th><td><?= number_format($balance_due, 2) ?> ৳</td></tr>
    </table>

    <a href="index.php?page=daily_payment/index" class="btn btn-secondary">🔙 তালিকায় ফিরে যান</a>
</body>
</html>
