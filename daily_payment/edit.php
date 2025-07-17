<?php
// DB সংযোগ
 
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

// ডেটা লোড
$stmt = $pdo->prepare("SELECT * FROM rental_payments WHERE payment_id = ?");
$stmt->execute([$payment_id]);
$payment = $stmt->fetch();

if (!$payment) {
    die("⚠️ পেমেন্ট তথ্য পাওয়া যায়নি।");
}

// ফর্ম সাবমিট হলে
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $sql = "UPDATE rental_payments SET
                    payment_date = :payment_date,
                    amount_paid = :amount_paid,
                    payment_method = :payment_method,
                    paid_for_period_start = :paid_for_period_start,
                    paid_for_period_end = :paid_for_period_end,
                    late_fee = :late_fee,
                    note = :note,
                    status = :status
                WHERE payment_id = :payment_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':payment_date' => $_POST['payment_date'],
            ':amount_paid' => $_POST['amount_paid'],
            ':payment_method' => $_POST['payment_method'],
            ':paid_for_period_start' => $_POST['paid_for_period_start'] ?: null,
            ':paid_for_period_end' => $_POST['paid_for_period_end'] ?: null,
            ':late_fee' => $_POST['late_fee'] ?: 0.00,
            ':note' => $_POST['note'] ?: null,
            ':status' => $_POST['status'],
            ':payment_id' => $payment_id
        ]);

        echo "<script>alert('✅ পেমেন্ট আপডেট সফল হয়েছে।'); window.location.href='view_rental_payment.php?id={$payment_id}';</script>";
    } catch (PDOException $e) {
        echo "❌ ত্রুটি: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>✏️ পেমেন্ট এডিট</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>✏️ পেমেন্ট এডিট</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">পেমেন্ট তারিখ</label>
            <input type="date" name="payment_date" class="form-control" value="<?= htmlspecialchars($payment['payment_date']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">পরিমাণ (৳)</label>
            <input type="number" name="amount_paid" class="form-control" step="0.01" value="<?= htmlspecialchars($payment['amount_paid']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">পেমেন্ট পদ্ধতি</label>
            <select name="payment_method" class="form-control">
                <?php
                $methods = ['Cash', 'Bank', 'Mobile Banking', 'Card', 'Other'];
                foreach ($methods as $method) {
                    $selected = ($payment['payment_method'] === $method) ? 'selected' : '';
                    echo "<option value=\"$method\" $selected>$method</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">পেইড পিরিয়ড শুরু</label>
            <input type="date" name="paid_for_period_start" class="form-control" value="<?= htmlspecialchars($payment['paid_for_period_start']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">পেইড পিরিয়ড শেষ</label>
            <input type="date" name="paid_for_period_end" class="form-control" value="<?= htmlspecialchars($payment['paid_for_period_end']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">বিলম্ব ফি (৳)</label>
            <input type="number" name="late_fee" class="form-control" step="0.01" value="<?= htmlspecialchars($payment['late_fee']) ?>">
        </div>
        <div class="col-md-12">
            <label class="form-label">নোট</label>
            <textarea name="note" class="form-control"><?= htmlspecialchars($payment['note']) ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label">স্ট্যাটাস</label>
            <select name="status" class="form-control">
                <?php
                $statuses = ['Paid', 'Partial', 'Advance'];
                foreach ($statuses as $status) {
                    $selected = ($payment['status'] === $status) ? 'selected' : '';
                    echo "<option value=\"$status\" $selected>$status</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-success">💾 আপডেট করুন</button>
            <a href="index.php?id=<?= $payment_id ?>" class="btn btn-secondary">🔙 ফিরে যান</a>
        </div>
    </form>
</body>
</html>
