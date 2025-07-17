<?php


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("⚠️ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

// কাস্টমার লোড
$stmt = $pdo->query("SELECT * FROM customer ORDER BY customer_id DESC");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grandVara = 0;
$grandKisti = 0;
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>👥 কাস্টমার রিপোর্ট</title>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="container mt-4">
    <h2 class="mb-3">📊 কাস্টমার রিপোর্ট (<?= htmlspecialchars($month) ?>/<?= htmlspecialchars($year) ?>)</h2>

    <form class="row g-2 mb-3 no-print" method="get" action="index.php">
        <input type="hidden" name="page" value="customer/report/customer_report">
        <div class="col-md-3">
            <select name="month" class="form-control" required>
                <option value="">মাস বাছাই করুন</option>
                <?php 
                foreach (range(1,12) as $m) {
                    $selected = ($m == $month) ? 'selected' : '';
                    printf('<option value="%02d" %s>%s</option>', $m, $selected, date('F', mktime(0,0,0,$m,1)));
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="year" class="form-control" value="<?= htmlspecialchars($year) ?>" min="2000" max="<?= date('Y') ?>" required placeholder="বছর (যেমন 2025)">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">🔍 রিপোর্ট দেখুন</button>
        </div>
        <div class="col-md-2">
            <a href="index.php?page=customer/report/customer_report" class="btn btn-outline-danger w-100">❌ রিসেট</a>
        </div>
        <div class="col-md-2">
            <button type="button" onclick="window.print()" class="btn btn-success w-100">🖨️ প্রিন্ট</button>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>নাম</th>
                <th>মোবাইল</th>
                <th>টাইপ</th>
                <th>মোট ভাড়া (৳)</th>
                <th>মোট কিস্তি (৳)</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($customers as $cus):
            // মোট ভাড়া হিসাব
            $stmtVara = $pdo->prepare("
                SELECT SUM(rent_amount) as totalVara
                FROM rentals 
                WHERE customer_id = :cid AND MONTH(start_date) = :month AND YEAR(start_date) = :year
            ");
            $stmtVara->execute([
                ':cid' => $cus['customer_id'],
                ':month' => $month,
                ':year' => $year
            ]);
            $totalVara = $stmtVara->fetchColumn() ?? 0;

            // মোট কিস্তি হিসাব
            $stmtKisti = $pdo->prepare("
                SELECT SUM(amount) as totalKisti
                FROM installment_payments 
                WHERE customer_id = :cid AND MONTH(payment_date) = :month AND YEAR(payment_date) = :year
            ");
            $stmtKisti->execute([
                ':cid' => $cus['customer_id'],
                ':month' => $month,
                ':year' => $year
            ]);
            $totalKisti = $stmtKisti->fetchColumn() ?? 0;

            $grandVara += $totalVara;
            $grandKisti += $totalKisti;
        ?>
            <tr>
                <td><?= $cus['customer_id'] ?></td>
                <td><?= htmlspecialchars($cus['first_name'] . ' ' . $cus['last_name']) ?></td>
                <td><?= htmlspecialchars($cus['phone']) ?></td>
                <td><?= htmlspecialchars($cus['customer_type']) ?></td>
                <td><?= number_format($totalVara, 2) ?></td>
                <td><?= number_format($totalKisti, 2) ?></td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($customers)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted">⚠️ কোনো কাস্টমার পাওয়া যায়নি।</td>
            </tr>
        <?php else: ?>
            <tr class="table-secondary fw-bold">
                <td colspan="4" class="text-end">মোট</td>
                <td><?= number_format($grandVara, 2) ?></td>
                <td><?= number_format($grandKisti, 2) ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
