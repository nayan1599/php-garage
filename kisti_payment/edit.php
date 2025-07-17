<?php
 try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}
 
$payment_id = $_GET['payment_id'];
// ডাটা লোড
$stmt = $pdo->prepare("
    SELECT p.*, c.first_name, c.last_name, g.gari_nam 
    FROM installment_payments p
    JOIN customer c ON p.customer_id = c.customer_id
    JOIN gari g ON p.gari_id = g.gari_id
    WHERE p.payment_id = ?
");
$stmt->execute([$payment_id]);
$payment = $stmt->fetch();

if (!$payment) {
    die("❌ পেমেন্ট রেকর্ড পাওয়া যায়নি!");
}

// ফর্ম সাবমিট
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];

    if (!empty($amount) && !empty($payment_date)) {
        $stmt = $pdo->prepare("UPDATE installment_payments SET amount = ?, payment_date = ? WHERE payment_id = ?");
        if ($stmt->execute([$amount, $payment_date, $payment_id])) {
            echo "<div class='alert alert-success'>✅ পেমেন্ট আপডেট সফল!</div>";
            // নতুন ডাটা লোড
            $stmt->execute([$payment_id]);
            $payment = $stmt->fetch();
        } else {
            echo "<div class='alert alert-danger'>❌ আপডেট ব্যর্থ!</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>⚠️ সব ফিল্ড পূরণ করুন!</div>";
    }
}
?>
 
<div class="container mt-4">
    <h2>📝 কিস্তি পেমেন্ট এডিট</h2>
 
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">ক্রেতার নাম</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($payment['first_name'].' '.$payment['last_name']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">গাড়ির নাম</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($payment['gari_nam']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">টাকার পরিমাণ</label>
                    <input type="number" name="amount" class="form-control" value="<?= htmlspecialchars($payment['amount']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">পেমেন্ট তারিখ</label>
                    <input type="date" name="payment_date" class="form-control" value="<?= htmlspecialchars($payment['payment_date']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                <a href="installment_dashboard.php" class="btn btn-secondary">ফিরে যান</a>

               
            </form>
        </div>
    </div>
</div>
 