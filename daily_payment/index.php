<?php
// DB সংযোগ
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// সার্চ কন্ডিশন
$where = "1";
$params = [];

if (!empty($_GET['search'])) {
    $where .= " AND (
        c.first_name LIKE ? OR 
        c.last_name LIKE ? OR 
        c.phone LIKE ? OR 
        g.gari_nam LIKE ?
    )";
    $search = "%" . $_GET['search'] . "%";
    $params = [$search, $search, $search, $search];
}

// ডেটা লোড
$stmt = $pdo->prepare(" SELECT p1.*
        , r.rental_id
        , c.first_name
        , c.last_name
        , c.phone
        , g.gari_nam
    FROM rental_payments p1
    INNER JOIN (
        SELECT rental_id, MAX(payment_id) AS max_payment_id
        FROM rental_payments
        GROUP BY rental_id
    ) p2 ON p1.payment_id = p2.max_payment_id
    JOIN rentals r ON p1.rental_id = r.rental_id
    JOIN customer c ON r.customer_id = c.customer_id
    JOIN gari g ON r.gari_id = g.gari_id
    WHERE $where
    ORDER BY p1.payment_id DESC
");
$stmt->execute($params);
$payments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>📋 ভাড়া পেমেন্ট লিস্ট</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>📋 ভাড়া পেমেন্ট লিস্ট</h2>

    <form class="row g-2 mb-3" method="get" action="index.php">
        <input type="hidden" name="page" value="daily_payment/index">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="নাম / মোবাইল / গাড়ি"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">🔍 সার্চ</button>
        </div>
        <div class="col-md-2">
            <a href="index.php" class="btn btn-outline-danger w-100">❌ রিসেট</a>
        </div>
    </form>
 <div class="mb-3 text-end">
        <a href="add_payment.php" class="btn btn-success">➕ নতুন পেমেন্ট যোগ করুন</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>কাস্টমার</th>
                <th>ফোন</th>
                <th>গাড়ি</th>
                <th>তারিখ</th>
                <th>পরিমাণ (৳)</th>
                <th>পদ্ধতি</th>
                <th>স্ট্যাটাস</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($payments): foreach ($payments as $p):
            
 
            
            
            ?>
            <tr>
                <td><?= $p['payment_id'] ?></td>
                <td><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></td>
                <td><?= htmlspecialchars($p['phone']) ?></td>
                <td><?= htmlspecialchars($p['gari_nam']) ?></td>
                <td><?= htmlspecialchars($p['payment_date']) ?></td>
                <td><?= number_format($p['amount_paid'], 2) ?></td>
                <td><?= htmlspecialchars($p['payment_method']) ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
                <td>
                
                    <a href="index.php?page=daily_payment/edit&id=<?= $p['payment_id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                    <a href="index.php?page=daily_payment/delete&id=<?= $p['payment_id'] ?>" class="btn btn-sm btn-danger">❌ Delete</a>
                    <a href="index.php?page=daily_payment/view&id=<?= $p['payment_id'] ?>" class="btn btn-sm btn-info">👁️ View</a>
                    <a href ="index.php?page=daily_payment/add&rental_id=<?= $p['rental_id'] ?>" class="btn btn-sm btn-success">➕ Add Payment</a>
                 </td>
            </tr>
        <?php endforeach; else: ?>
            <tr>
                <td colspan="9" class="text-center text-muted">⚠️ কোনো পেমেন্ট পাওয়া যায়নি।</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
