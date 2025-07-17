<?php
 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// মোট কিস্তি বিক্রয় সংখ্যা
$total_sales = $pdo->query("SELECT COUNT(*) FROM installment_sales")->fetchColumn();

// মোট বিক্রির পরিমাণ
$total_amount = $pdo->query("SELECT SUM(total_price) FROM installment_sales")->fetchColumn();
$total_amount = $total_amount ?: 0;

// আজকের কিস্তি পেমেন্ট সংখ্যা
$today = date('Y-m-d');
$today_payments = $pdo->prepare("SELECT COUNT(*) FROM installment_payments WHERE payment_date = ?");
$today_payments->execute([$today]);
$today_payments_count = $today_payments->fetchColumn();

// শেষ ৫টি কিস্তি বিক্রয় লোড
$last_sales = $pdo->query("
    SELECT s.installment_id, s.start_date, c.first_name, c.last_name, g.gari_nam, s.total_price, g.registration_number
    FROM installment_sales s
    JOIN customer c ON s.customer_id = c.customer_id
    JOIN gari g ON s.gari_id = g.gari_id
    ORDER BY s.start_date DESC
    LIMIT 5
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>কিস্তি বিক্রয় ড্যাশবোর্ড</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="mb-4">🚘 কিস্তি বিক্রয় ড্যাশবোর্ড</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">মোট কিস্তি বিক্রয়</h5>
                    <p class="card-text fs-3"><?= $total_sales ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">মোট বিক্রির পরিমাণ</h5>
                    <p class="card-text fs-3"><?= number_format($total_amount, 2) ?> ৳</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">আজকের কিস্তি সংগ্রহ</h5>
                    <p class="card-text fs-3"><?= $today_payments_count ?></p>
                </div>
            </div>
        </div>
    </div>

    <h4>📌 সর্বশেষ ৫টি কিস্তি বিক্রয়</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>আইডি</th>
                <th>তারিখ</th>
                <th>কাস্টমার</th>
                <th>গাড়ি</th>
                <th>পরিমাণ</th>
                <th>👁️</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($last_sales as $sale): ?>
            <tr>
                <td><?= $sale['installment_id'] ?></td>
                <td><?= date('d-m-Y', strtotime($sale['start_date'])) ?></td>
                <td><?= htmlspecialchars($sale['first_name'] . ' ' . $sale['last_name']) ?></td>
                <td><?= htmlspecialchars($sale['gari_nam']. ' - '. $sale['registration_number']) ?></td>
                <td><?= number_format($sale['total_price'], 2) ?> ৳</td>
                <td><a href="index.php?page=kisti_form/view&installment_id=<?= $sale['installment_id'] ?>" class="btn btn-sm btn-info">👁️ দেখুন</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
