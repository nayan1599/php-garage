<?php
// DB কানেকশন


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেস সংযোগ ব্যর্থ: " . $e->getMessage());
}

// সার্চ কন্ডিশন
$where = "1";
$params = [];

if (!empty($_GET['search'])) {
    $where .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR g.gari_nam LIKE ?)";
    $params[] = "%" . $_GET['search'] . "%";
    $params[] = "%" . $_GET['search'] . "%";
    $params[] = "%" . $_GET['search'] . "%";
}

// ডেটা লোড
$stmt = $pdo->prepare("
    SELECT s.*, c.first_name, c.last_name, g.gari_nam, g.registration_number
    FROM installment_sales s
    JOIN customer c ON s.customer_id = c.customer_id
    JOIN gari g ON s.gari_id = g.gari_id
    WHERE $where
    ORDER BY s.installment_id DESC
");
$stmt->execute($params);
$sales = $stmt->fetchAll();
?>

 

<body class="container mt-4">

    <h2 class="mb-3">📄 কিস্তি বিক্রির তালিকা</h2>

    <form class="row g-2 mb-3" method="get" action="index.php?page=kisti_form/index">
        <input type="hidden" name="page" value="kisti_form/index">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="কাস্টমার বা গাড়ির নাম">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔍 সার্চ</button>
        </div>
        <div class="col-md-2">
            <a href="index.php?page=kisti_form/index" class="btn btn-outline-danger w-100">❌ রিসেট</a>
        </div>
    </form>

    <div class="mb-3 text-end">
        <a href="index.php?page=kisti_form/add" class="btn btn-success">➕ নতুন কিস্তি বিক্রয়</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>কাস্টমার</th>
                <th>গাড়ি - NO</th>
                <th>তারিখ</th>
                <th>মোট</th>
                <th>ডাউন</th>
                <th>পরিশোধ</th>
                <th>বাকি</th>
                <th>স্ট্যাটাস</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($sales): foreach ($sales as $row):
                // মোট পরিশোধিত টাকা বের করা
                $payStmt = $pdo->prepare("SELECT SUM(amount + late_fee) AS total_paid FROM installment_payments WHERE installment_id = ?");
                $payStmt->execute([$row['installment_id']]);
                $pay = $payStmt->fetch(PDO::FETCH_ASSOC);
                $total_paid = $pay['total_paid'] ?? 0;

                // বাকি টাকা
                $due = $row['total_price'] - $row['down_payment'] - $total_paid;
                if ($due < 0) $due = 0;
            ?>
            <tr>
                <td><?= $row['installment_id'] ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['gari_nam'].'-'.$row['registration_number']) ?></td>
                <td><?= date('d-m-Y', strtotime($row['start_date'])) ?></td>
                <td><?= number_format($row['total_price'], 2) ?> ৳</td>
                <td><?= number_format($row['down_payment'], 2) ?> ৳</td>
                <td><?= number_format($total_paid, 2) ?> ৳</td>
                <td><?= number_format($due, 2) ?> ৳</td>
                <td>
                    <?php if ($due == 0): ?>
                        <span class="badge bg-success">✅ পরিশোধিত</span>
                    <?php else: ?>
                        <span class="badge bg-warning">⚠️ চলমান</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="index.php?page=kisti_form/view&installment_id=<?= $row['installment_id'] ?>" class="btn btn-sm btn-info">👁️ দেখুন</a>
                    <a href="index.php?page=kisti_form/edit&installment_id=<?= $row['installment_id'] ?>" class="btn btn-sm btn-warning">✏️ এডিট</a>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="10" class="text-center text-muted">⚠️ কোনো কিস্তি বিক্রির তথ্য পাওয়া যায়নি।</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
 