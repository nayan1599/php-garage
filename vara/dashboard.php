<?php
 
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ কানেকশন ব্যর্থ: " . $e->getMessage());
}

$today = date('Y-m-d');

// আজকের মোট ভাড়া আদায়
$stmt = $conn->prepare("SELECT SUM(amount) FROM vara_payments WHERE payment_date = ?");
$stmt->execute([$today]);
$today_total = $stmt->fetchColumn();
$today_total = $today_total ?: 0;

// আজকের মোট এন্ট্রি সংখ্যা
$count_stmt = $conn->prepare("SELECT COUNT(*) FROM vara_payments WHERE payment_date = ?");
$count_stmt->execute([$today]);
$today_entries_count = $count_stmt->fetchColumn();

// আজকের সর্বশেষ ১০টি এন্ট্রি
$recent_stmt = $conn->prepare("SELECT payer_name, gari_no, amount, payment_date FROM vara_payments WHERE payment_date = ? ORDER BY payment_id DESC LIMIT 10");
$recent_stmt->execute([$today]);
$recent_entries = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8" />
  <title>আজকের ভাড়া আদায় ড্যাশবোর্ড</title>
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4 text-center text-success">💼 আজকের ভাড়া আদায় ড্যাশবোর্ড</h2>

  <div class="row mb-4 justify-content-center">
    <div class="col-md-4">
      <div class="card text-bg-success shadow mb-3">
        <div class="card-body text-center">
          <h5 class="card-title">আজকের মোট ভাড়া</h5>
          <p class="card-text fs-3">৳ <?= number_format($today_total, 2) ?></p>
          <small class="text-muted"><?= date('d M Y') ?></small>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-bg-primary shadow mb-3">
        <div class="card-body text-center">
          <h5 class="card-title">আজকের মোট ভাড়া এন্ট্রি</h5>
          <p class="card-text fs-3"><?= $today_entries_count ?></p>
          <small class="text-muted"><?= date('d M Y') ?></small>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow">
    <div class="card-header bg-dark text-white">
      আজকের সর্বশেষ ১০টি ভাড়া এন্ট্রি
    </div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th>নাম</th>
            <th>গাড়ির নাম্বার</th>
            <th>পরিমাণ</th>
            <th>তারিখ</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($recent_entries): ?>
            <?php foreach ($recent_entries as $entry): ?>
              <tr>
                <td><?= htmlspecialchars($entry['payer_name']) ?></td>
                <td><?= htmlspecialchars($entry['gari_no']) ?></td>
                <td>৳ <?= number_format($entry['amount'], 2) ?></td>
                <td><?= htmlspecialchars($entry['payment_date']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center">কোনো এন্ট্রি পাওয়া যায়নি</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
