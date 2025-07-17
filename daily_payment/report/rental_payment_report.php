<?php
 
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ কানেকশন ব্যর্থ: " . $e->getMessage());
}

// ফিল্টার
$where = "1";
$params = [];

if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $where .= " AND p.payment_date BETWEEN :from_date AND :to_date";
    $params[':from_date'] = $_GET['from_date'];
    $params[':to_date'] = $_GET['to_date'];
}

// পেমেন্ট রেকর্ড
$sql = "SELECT p.*, c.first_name, c.last_name, g.gari_nam
        FROM rental_payments p
        JOIN customer c ON p.customer_id = c.customer_id
        JOIN gari g ON p.gari_id = g.gari_id
        WHERE $where
        ORDER BY p.payment_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// কার্ডের ডাটা
$card_sql = "SELECT COUNT(*) AS total_payments, SUM(p.total_paid) AS total_amount
             FROM rental_payments p
             WHERE $where";
$card_stmt = $conn->prepare($card_sql);
$card_stmt->execute($params);
$card_data = $card_stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>ভাড়া পেমেন্ট রিপোর্ট</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        .card-custom {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-custom {
            min-width: 120px;
        }
    </style>
</head>
<body class="container mt-4">
    <h3 class="mb-4 text-center bg-dark text-white p-2 rounded">🚗 ভাড়া পেমেন্ট রিপোর্ট</h3>

    <form method="GET" class="row g-2 mb-4 bg-light p-3 rounded" action="index.php?page=daily_payment/report/rental_payment_report">
        <input type="hidden" name="page" value="daily_payment/report/rental_payment_report">
        <div class="col-md-3">
            <label>শুরুর তারিখ</label>
            <input type="date" name="from_date" class="form-control" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label>শেষ তারিখ</label>
            <input type="date" name="to_date" class="form-control" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary btn-custom">সার্চ</button>
            <a href="index.php?page=daily_payment/report/rental_payment_report" class="btn btn-secondary btn-custom">রিসেট</a>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-success card-custom">
                <div class="card-body text-center">
                    <h5 class="card-title">মোট পেমেন্ট সংখ্যা</h5>
                    <p class="card-text fs-3"><?= htmlspecialchars($card_data['total_payments'] ?? 0) ?> টি</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-primary card-custom">
                <div class="card-body text-center">
                    <h5 class="card-title">মোট ভাড়া (৳)</h5>
                    <p class="card-text fs-3"><?= htmlspecialchars($card_data['total_amount'] ?? 0) ?> ৳</p>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>তারিখ</th>
                <th>কাস্টমার</th>
                <th>গাড়ি</th>
                <th>পরিমাণ</th>
                <th>পদ্ধতি</th>
                <th>মন্তব্য</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($payments): ?>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['payment_date']) ?></td>
                        <td><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></td>
                        <td><?= htmlspecialchars($p['gari_nam']) ?></td>
                        <td><?= htmlspecialchars($p['total_paid']) ?> ৳</td>
                        <td><?= htmlspecialchars($p['payment_method']) ?></td>
                        <td><?= htmlspecialchars($p['note']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center text-danger">❌ কোন পেমেন্ট রেকর্ড পাওয়া যায়নি</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="d-flex justify-content-center gap-2 mt-4">
        <button onclick="window.print()" class="btn btn-success btn-custom">প্রিন্ট</button>
        <button class="btn btn-outline-primary btn-custom" onclick="alert('✅ এক্সেল ডাউনলোড ফিচার সেট করুন')">এক্সেল</button>
        <button class="btn btn-outline-danger btn-custom" onclick="alert('✅ PDF ডাউনলোড ফিচার সেট করুন')">PDF</button>
    </div>
</body>
</html>
