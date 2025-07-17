<?php
 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    // মোট ভাড়া পেমেন্ট
    $totalStmt = $pdo->query("
        SELECT COUNT(*) AS total_rents, 
               COALESCE(SUM(amount_due), 0) AS total_due, 
               COALESCE(SUM(amount_paid), 0) AS total_paid, 
               COALESCE(SUM(late_fee), 0) AS total_late
        FROM rental_payments
    ");
    $totalData = $totalStmt->fetch(PDO::FETCH_ASSOC);

    // আজকের পেমেন্ট
    $todayStmt = $pdo->prepare("
        SELECT COUNT(*) AS today_rents, 
               COALESCE(SUM(amount_due), 0) AS today_due, 
               COALESCE(SUM(amount_paid), 0) AS today_paid, 
               COALESCE(SUM(late_fee), 0) AS today_late
        FROM rental_payments
        WHERE payment_date = ?
    ");
    $todayStmt->execute([$today]);
    $todayData = $todayStmt->fetch(PDO::FETCH_ASSOC);

    // সর্বশেষ ৫টি পেমেন্ট
    $recentStmt = $pdo->query("
        SELECT p.payment_id, c.first_name, c.last_name, p.amount_due, p.amount_paid, p.late_fee, p.status, p.payment_date
        FROM rental_payments p
        JOIN customer c ON p.customer_id = c.customer_id
        ORDER BY p.payment_date DESC, p.payment_id DESC
        LIMIT 5
    ");
    $recentRents = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

    // আজকে যেসব কাস্টমার ভাড়া নিয়েছে
    $todayRentalStmt = $pdo->prepare("
        SELECT DISTINCT r.customer_id
        FROM rentals r
        WHERE r.end_date = ?
    ");
    $todayRentalStmt->execute([$today]);
    $today_customers = $todayRentalStmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($today_customers)) {
        $tomorrow_customers_list = [];
        $count = 0;
    } else {
        $in_clause = implode(',', array_fill(0, count($today_customers), '?'));
        $tomorrowStmt = $pdo->prepare("
            SELECT DISTINCT c.customer_id, c.first_name, c.last_name, c.phone
            FROM rentals r
            JOIN customer c ON r.customer_id = c.customer_id
            WHERE r.end_date = ? AND r.customer_id IN ($in_clause)
        ");

        $params = array_merge([$tomorrow], $today_customers);
        $tomorrowStmt->execute($params);
        $tomorrow_customers_list = $tomorrowStmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($tomorrow_customers_list);
    }

} catch (PDOException $e) {
    die("⚠️ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>🚘 ভাড়া ড্যাশবোর্ড</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1>🚘 ভাড়া ড্যাশবোর্ড</h1>
    <hr>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h4>মোট পেমেন্ট</h4>
                    <h2><?= $totalData['total_rents'] ?> টি</h2>
                    <p>মোট ভাড়া: ৳ <?= number_format($totalData['total_due'], 2) ?></p>
                    <p>মোট পরিশোধ: ৳ <?= number_format($totalData['total_paid'], 2) ?></p>
                    <p>মোট লেট ফি: ৳ <?= number_format($totalData['total_late'], 2) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h4>আজকের পেমেন্ট</h4>
                    <h2><?= $todayData['today_rents'] ?> টি</h2>
                    <p>আজকের ভাড়া: ৳ <?= number_format($todayData['today_due'], 2) ?></p>
                    <p>আজকের পরিশোধ: ৳ <?= number_format($todayData['today_paid'], 2) ?></p>
                    <p>আজকের লেট ফি: ৳ <?= number_format($todayData['today_late'], 2) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <h4>🔗 দ্রুত লিঙ্ক</h4>
                    <a href="index.php?page=daily_payment/add" class="btn btn-sm btn-success w-100 mb-2">➕ নতুন পেমেন্ট</a>
                    <a href="index.php?page=daily_payment/index" class="btn btn-sm btn-primary w-100">📋 পেমেন্ট তালিকা</a>
                </div>
            </div>
        </div>
    </div>

    <!-- আজকে ভাড়া নিয়েছে এবং কালও নেবে এমন কাস্টমারদের তালিকা -->
    <div class="card mt-4">
        <div class="card-header bg-warning text-dark">
            আজ ভাড়া নিয়েছে এবং কালও নেবে এমন কাস্টমারের সংখ্যা: <?= $count ?>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>কাস্টমার আইডি</th>
                        <th>নাম</th>
                        <th>ফোন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($count > 0): ?>
                        <?php foreach ($tomorrow_customers_list as $cust): ?>
                            <tr>
                                <td><?= $cust['customer_id'] ?></td>
                                <td><?= htmlspecialchars($cust['first_name'] . ' ' . $cust['last_name']) ?></td>
                                <td><?= htmlspecialchars($cust['phone']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">⚠️ কোনো কাস্টমার পাওয়া যায়নি</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            সর্বশেষ ৫টি পেমেন্ট
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>পেমেন্ট আইডি</th>
                        <th>কাস্টমার</th>
                        <th>ভাড়া (৳)</th>
                        <th>পরিশোধ (৳)</th>
                        <th>লেট ফি (৳)</th>
                        <th>স্ট্যাটাস</th>
                        <th>তারিখ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recentRents): ?>
                        <?php foreach ($recentRents as $rent): ?>
                            <tr>
                                <td><?= $rent['payment_id'] ?></td>
                                <td><?= htmlspecialchars($rent['first_name'] . ' ' . $rent['last_name']) ?></td>
                                <td><?= number_format($rent['amount_due'], 2) ?></td>
                                <td><?= number_format($rent['amount_paid'], 2) ?></td>
                                <td><?= number_format($rent['late_fee'], 2) ?></td>
                                <td><?= $rent['status'] ?></td>
                                <td><?= $rent['payment_date'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted">⚠️ কোনো পেমেন্ট পাওয়া যায়নি</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">🏠 মেইন ড্যাশবোর্ড</a>
</body>
</html>
