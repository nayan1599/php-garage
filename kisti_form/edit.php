<?php
// DB কানেকশন
include '../config/db.php'; // নিশ্চিত করুন এই ফাইলটি ডাটাবেস কানেকশন করে
include '../config/session_check.php';  // সেশন চেক
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেস সংযোগ ব্যর্থ: " . $e->getMessage());
}

// পেমেন্ট আইডি যাচাই
if (empty($_GET['id'])) {
    die("❌ পেমেন্ট আইডি প্রদান করুন।");
}

$payment_id = $_GET['id'];

// ফর্ম সাবমিট হলে
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $sql = "UPDATE installment_payments SET 
                    payment_date = :payment_date,
                    amount = :amount,
                    payment_method = :payment_method,
                    late_fee = :late_fee,
                    paid_for_month = :paid_for_month,
                    note = :note,
                    status = :status
                WHERE payment_id = :payment_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':payment_date'    => $_POST['payment_date'],
            ':amount'          => $_POST['amount'],
            ':payment_method'  => $_POST['payment_method'],
            ':late_fee'        => $_POST['late_fee'],
            ':paid_for_month'  => $_POST['paid_for_month'],
            ':note'            => $_POST['note'],
            ':status'          => $_POST['status'],
            ':payment_id'      => $payment_id
        ]);

        echo "<script>alert('✅ পেমেন্ট সফলভাবে আপডেট হয়েছে'); window.location.href='view_installment_payment.php?id=$payment_id';</script>";
    } catch (PDOException $e) {
        echo "❌ ত্রুটি: " . $e->getMessage();
    }
} else {
    // ডেটা লোড
    $stmt = $pdo->prepare("
        SELECT * FROM installment_payments WHERE payment_id = ?
    ");
    $stmt->execute([$payment_id]);
    $payment = $stmt->fetch();

    if (!$payment) {
        die("❌ পেমেন্ট খুঁজে পাওয়া যায়নি।");
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>✏️ কিস্তি পেমেন্ট এডিট</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="mb-3">✏️ কিস্তি পেমেন্ট এডিট</h2>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">পেমেন্ট তারিখ</label>
            <input type="date" name="payment_date" class="form-control" value="<?= $payment['payment_date'] ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">পরিমাণ (৳)</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="<?= $payment['amount'] ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">পেমেন্ট মাধ্যম</label>
            <select name="payment_method" class="form-select" required>
                <?php
                $methods = ['Cash', 'Bank', 'Mobile Banking', 'Card', 'Other'];
                foreach ($methods as $method) {
                    $selected = $payment['payment_method'] === $method ? 'selected' : '';
                    echo "<option value=\"$method\" $selected>$method</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">বিলম্ব ফি (৳)</label>
            <input type="number" step="0.01" name="late_fee" class="form-control" value="<?= $payment['late_fee'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">যে মাসের জন্য</label>
            <input type="text" name="paid_for_month" class="form-control" value="<?= htmlspecialchars($payment['paid_for_month']) ?>" placeholder="YYYY-MM">
        </div>
        <div class="col-md-6">
            <label class="form-label">স্ট্যাটাস</label>
            <select name="status" class="form-select" required>
                <?php
                $statuses = ['Paid', 'Partial', 'Advance'];
                foreach ($statuses as $status) {
                    $selected = $payment['status'] === $status ? 'selected' : '';
                    echo "<option value=\"$status\" $selected>$status</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-12">
            <label class="form-label">নোট</label>
            <textarea name="note" class="form-control"><?= htmlspecialchars($payment['note']) ?></textarea>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-success">💾 আপডেট করুন</button>
            <a href="view.php?id=<?= $payment_id ?>" class="btn btn-secondary">🔙 ফিরে যান</a>
        </div>
    </form>
</body>
</html>
