<?php
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেস সংযোগ ব্যর্থ: " . $e->getMessage());
}

// ইনস্টলমেন্ট আইডি যাচাই
if (empty($_GET['installment_id'])) {
    die("❌ ইনস্টলমেন্ট আইডি প্রদান করুন।");
}

$installment_id = $_GET['installment_id'];

// ইনস্টলমেন্ট বিক্রয় ডেটা লোড
$stmt = $pdo->prepare("
    SELECT s.*, c.first_name, c.last_name, g.gari_nam, g.registration_number
    FROM installment_sales s
    JOIN customer c ON s.customer_id = c.customer_id
    JOIN gari g ON s.gari_id = g.gari_id
    WHERE s.installment_id = ?
");
$stmt->execute([$installment_id]);
$sale = $stmt->fetch();

if (!$sale) {
    die("❌ ইনস্টলমেন্ট বিক্রয় খুঁজে পাওয়া যায়নি।");
}

// পেমেন্ট হিস্ট্রি লোড
$stmt = $pdo->prepare("
    SELECT * FROM installment_payments
    WHERE installment_id = ?
    ORDER BY payment_date DESC
");
$stmt->execute([$installment_id]);
$payments = $stmt->fetchAll();


 


// মোট পরিশোধ হিসাব
$total_paid = 0;
foreach ($payments as $p) {
    $total_paid += $p['amount'] + $p['late_fee'];
}

// বাকি কিস্তি সংখ্যা
$sale['remaining_installments'] = $sale['total_installments'] - count($payments);
// বিলম্ব ফি 
$sale['late_fee'] = $sale['late_fee'] ?? 0.00; // বিলম্ব ফি ডিফল্ট মান 0.00

// মোট পরিশোধ হিসাব
$total_paid = 0;
$total_late_fee = 0;
foreach ($payments as $p) {
    $total_paid += $p['amount'] + $p['late_fee'];
    $total_late_fee += $p['late_fee'];
}

$sale['remaining_installments'] = $sale['total_installments'] - count($payments);
$total_paid += $sale['down_payment'];
$due = $sale['total_price'] - $total_paid;


// বাকি পরিমাণ হিসাব
$due = $sale['total_price'] - $total_paid;
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>📄 কিস্তি বিক্রয় বিস্তারিত</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="mb-3 text-center">📄 কিস্তি বিক্রয় বিস্তারিত</h2>









  <table class="table table-bordered">
    <tbody>
        <tr>
            <th width="30%">ক্রেতা</th>
            <td><?= htmlspecialchars($sale['first_name'] . ' ' . $sale['last_name']) ?></td>
        </tr>
        <tr>
            <th>গাড়ি</th>
            <td><?= htmlspecialchars($sale['gari_nam']) ?> (<?= htmlspecialchars($sale['registration_number']) ?>)</td>
        </tr>
        <tr>
            <th>বিক্রয় তারিখ</th>
            <td><?= $sale['start_date'] ?></td>
        </tr>
        <tr>
            <th>মূল্য (৳)</th>
            <td><?= number_format($sale['total_price'], 2) ?></td>
        </tr>
        <tr>
            <th>এডভান্স পেমেন্ট (৳)</th>
            <td><?= number_format($sale['down_payment'], 2) ?></td>
        </tr>
        <tr>
            <th>মাসিক কিস্তি (৳)</th>
            <td><?= number_format($sale['installment_amount'], 2) ?></td>
        </tr>
        <tr>
            <th>মোট কিস্তি সংখ্যা</th>
            <td><?= $sale['total_installments'] ?></td>
        </tr>
        <tr>
            <th>বাকি কিস্তি সংখ্যা</th>
            <td><?= $sale['remaining_installments'] ?></td>
        </tr>
        <tr>
            <th>বিলম্ব ফি (৳)</th>
            <td><?= number_format($sale['penalty_amount_fixed'], 2) ?></td>
        </tr>
        <tr>
            <th>মোট মূল্য (৳)</th>
            <td><?= number_format($sale['total_price'] + $sale['late_fee'], 2) ?></td>
        </tr>
        <tr>
            <th>মোট কিস্তি পরিশোধ (৳)</th>
            <td><?= number_format($sale['installment_amount'] * ($sale['total_installments'] - $sale['remaining_installments']), 2) ?></td>
        </tr>
      <tr>
    <th>মোট বিলম্ব ফি (৳)</th>
    <td><?= number_format($total_late_fee, 2) ?></td>
</tr>
        <tr>
            <th>মোট পরিশোধ (৳)</th>
            <td><?= number_format($total_paid, 2) ?></td>
        </tr>
        <tr>
            <th>বাকি (৳)</th>
            <td><?= number_format($due, 2) ?></td>
        </tr>
      
   


    </tbody>
</table>

    <h4>💵 পেমেন্ট হিস্ট্রি</h4>
    <?php if ($payments): ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>তারিখ</th>
                <th>পরিমাণ (৳)</th>
                <th>মাধ্যম</th>
                <th>বিলম্ব ফি (৳)</th>
                <th>মোট (৳)</th>
                <th>যে মাসের জন্য</th>
                <th>স্ট্যাটাস</th>
                <th>নোট</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $p): ?>
            <tr>
                <td><?= $p['payment_date'] ?></td>
                <td><?= number_format($p['amount'], 2) ?></td>
                <td><?= $p['payment_method'] ?></td>
                <td><?= number_format($p['late_fee'], 2) ?></td>
                <td><?= number_format($p['amount'] + $p['late_fee'], 2) ?></td>
                <td><?= htmlspecialchars($p['paid_for_month']) ?></td>
                <td><?= $p['status'] ?></td>
                <td><?= nl2br(htmlspecialchars($p['note'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="text-muted">❗ কোনো পেমেন্ট পাওয়া যায়নি।</p>
    <?php endif; ?>

    <a href="index.php?page=kisti_payment/index" class="btn btn-secondary mt-3">🔙 তালিকায় ফিরে যান</a>
</body>
</html>
