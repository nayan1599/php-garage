<?php
 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

$records = [];
$total_amount = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    if (!empty($from_date) && !empty($to_date)) {
        $stmt = $pdo->prepare("
            SELECT p.*, c.first_name, c.last_name, g.gari_nam
            FROM installment_payments p
            JOIN customer c ON p.customer_id = c.customer_id
            JOIN gari g ON p.gari_id = g.gari_id
            WHERE p.payment_date BETWEEN ? AND ?
            ORDER BY p.payment_date ASC
        ");
        $stmt->execute([$from_date, $to_date]);
        $records = $stmt->fetchAll();

        $stmt = $pdo->prepare("
            SELECT SUM(amount) AS total_amount
            FROM installment_payments
            WHERE payment_date BETWEEN ? AND ?
        ");
        $stmt->execute([$from_date, $to_date]);
        $total_amount = $stmt->fetch()['total_amount'] ?? 0;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>কিস্তি রিপোর্ট</title>
    <link href="../assets/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-custom { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .table-striped>tbody>tr:nth-of-type(odd)>* { background-color: #f9f9f9; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">📊 কিস্তি রিপোর্ট</h2>

    <div class="card card-custom p-4 mb-4">
        <form method="POST" class="row g-3" action="index.php?page=kisti_payment/report/kisti_form_report">
            <input type="hidden" name="page" value="kisti_payment/report/kisti_form_report">
            <div class="col-md-4">
                <label class="form-label">শুরুর তারিখ</label>
                <input type="date" name="from_date" class="form-control" required value="<?= $_POST['from_date'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">শেষ তারিখ</label>
                <input type="date" name="to_date" class="form-control" required value="<?= $_POST['to_date'] ?? '' ?>">
            </div>
            <div class="col-md-4 d-grid align-self-end">
                <button type="submit" class="btn btn-primary">🔍 রিপোর্ট দেখুন</button>
            </div>
        </form>
    </div>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <div class="card card-custom bg-light mb-3 p-3">
            <h5 class="mb-0">মোট কিস্তি আদায়: <span class="text-success fw-bold">৳ <?= number_format($total_amount, 2) ?></span></h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>ক্রেতার নাম</th>
                        <th>গাড়ির নাম</th>
                        <th>পরিমাণ</th>
                        <th>পেমেন্ট তারিখ</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($records): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td><?= $row['payment_id'] ?></td>
                        <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['gari_nam']) ?></td>
                        <td>৳ <?= number_format($row['amount'], 2) ?></td>
                        <td><?= $row['payment_date'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">⚠️ কোনো রেকর্ড পাওয়া যায়নি!</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <button class="btn btn-outline-secondary" onclick="window.print()">🖨️ প্রিন্ট</button>
            <!-- Future: Export options -->
            <!-- <button class="btn btn-outline-success">⬇️ Excel</button> -->
            <!-- <button class="btn btn-outline-danger">⬇️ PDF</button> -->
        </div>
    <?php endif; ?>
</div>
</body>
</html>
