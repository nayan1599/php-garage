<?php
 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}
// সার্চ ফিল্টার
$where = "1";
$params = [];

if (!empty($_GET['search'])) {
    $where .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR g.gari_nam LIKE ?)";
    $params[] = "%" . $_GET['search'] . "%";
    $params[] = "%" . $_GET['search'] . "%";
    $params[] = "%" . $_GET['search'] . "%";
}

// পেমেন্ট লিস্ট লোড
$sql = "SELECT p.*, c.first_name, c.last_name, g.gari_nam 
        FROM installment_payments p
        JOIN customer c ON p.customer_id = c.customer_id
        JOIN gari g ON p.gari_id = g.gari_id
        WHERE $where
        ORDER BY p.payment_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$payments = $stmt->fetchAll();

// print_r($payments);
?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>📄 কিস্তি পেমেন্ট তালিকা</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

    <h2 class="mb-3">📄 কিস্তি পেমেন্ট তালিকা</h2>

    <form method="get" class="row g-2 mb-3" action="index.php">
        <input type="hidden" name="page" value="kisti_form/index">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="কাস্টমার বা গাড়ির নাম">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔍 খুঁজুন</button>
        </div>
        <div class="col-md-2">
            <a href="index.php?page=kisti_form/index" class="btn btn-outline-secondary w-100">❌ রিসেট</a>
        </div>
    </form>
    <div class="mb-3 text-end">
        <a href="index.php?page=kisti_payment/add" class="btn btn-success">➕ নতুন কিস্তি পেমেন্ট যোগ করুন</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>কাস্টমার</th>
                <th>গাড়ি</th>
                <th>তারিখ</th>
                <th>পরিমাণ (৳)</th>
                <th>মন্তব্য</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($payments): ?>
                <?php foreach ($payments as $row): ?>
                    <tr>
                        <td><?= $row['payment_id'] ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['gari_nam']) ?></td>
                        <td><?= htmlspecialchars($row['payment_date']) ?></td>
                        <td><?= number_format($row['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($row['note']) ?></td>
                        <td>
                            <a href="index.php?page=kisti_payment/view&installment_id=<?= $row['installment_id'] ?>" class="btn btn-sm btn-info">👁️ দেখুন</a>
                            <a href="index.php?page=kisti_payment/edit&id=<?= $row['payment_id'] ?>" class="btn btn-warning">✏️ এডিট</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">⚠️ কোনো পেমেন্ট পাওয়া যায়নি।</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>