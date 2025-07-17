<?php
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ কানেকশন ব্যর্থ: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payer_name = $_POST['payer_name'];
    $gari_no = $_POST['gari_no'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];

    try {
        $sql = "INSERT INTO vara_payments (payer_name, gari_no, amount, payment_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$payer_name, $gari_no, $amount, $payment_date]);

        echo "<script>alert('✅ পেমেন্ট সফলভাবে যোগ হয়েছে'); window.location.href='index.php?page=vara/index';</script>";
        exit;
    } catch (Exception $e) {
        echo "❌ ত্রুটি: " . $e->getMessage();
    }
}
?>


<!-- HTML ফর্ম কেবল তখনি লোড হবে যখন POST না -->
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>💵 ভাড়া আদায় ফর্ম</title>
 
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">💵 ভাড়া আদায় ফর্ম</h4>
    </div>
    <div class="card-body">

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">❌ ত্রুটি হয়েছে: <?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">নাম</label>
          <input type="text" name="payer_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">গাড়ির নাম্বার</label>
          <input type="text" name="gari_no" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">টাকার পরিমাণ</label>
          <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">তারিখ</label>
          <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <button type="submit" class="btn btn-success">💾 সেভ করুন</button>
        <a href="index.php?page=vara/index" class="btn btn-secondary">📄 তালিকা</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
