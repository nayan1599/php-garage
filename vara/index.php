<?php


try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("❌ ডাটাবেজ কানেকশন ব্যর্থ: " . $e->getMessage());
}

// সার্চ প্রিপারেশন
$search = $_GET['search'] ?? '';
$date = $_GET['date'] ?? '';

// ডাইনামিক WHERE
$where = [];
$params = [];

if (!empty($search)) {
  $where[] = "(payer_name LIKE :search OR gari_no LIKE :search)";
  $params[':search'] = "%$search%";
}

if (!empty($date)) {
  $where[] = "payment_date = :date";
  $params[':date'] = $date;
}

// মেইন কুয়েরি - সর্বশেষ ১০ রেকর্ড
$sql = "SELECT * FROM vara_payments";
if ($where) {
  $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY payment_date DESC, payment_id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// মোট টাকার হিসাব ও মোট এন্ট্রি
$total_sql = "SELECT SUM(amount) AS total_amount, COUNT(*) AS total_entries FROM vara_payments";
if ($where) {
  $total_sql .= " WHERE " . implode(" AND ", $where);
}
$total_stmt = $conn->prepare($total_sql);
$total_stmt->execute($params);
$total_data = $total_stmt->fetch(PDO::FETCH_ASSOC);
$total_amount = $total_data['total_amount'] ?: 0;
$total_entries = $total_data['total_entries'] ?: 0;
?>

<style>
  @media print {

    .no-print,
    .no-print * {
      display: none !important;
    }

    body {
      background: #fff;
    }

    table {
      font-size: 12pt;
    }

    body * {
      visibility: hidden;
    }

    .print-area,
    .print-area * {
      visibility: visible;
    }

    .print-area {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
    }
  }
</style>
<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h2 class="text-success">💵 ভাড়া তালিকা</h2>
    <button onclick="window.print()" class="btn btn-dark">🖨️ প্রিন্ট</button>
  </div>

  <!-- ফিল্টার ফর্ম -->
  <form method="GET" class="row g-2 mb-4 no-print" action="index.php">
    <input type="hidden" name="page" value="vara/index">
    <div class="col-md-4">
      <input type="text" name="search" class="form-control" placeholder="নাম বা গাড়ি নাম্বার" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-3">
      <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">🔍 খুঁজুন</button>
    </div>
    <div class="col-md-2">
      <a href="index.php?page=vara/index" class="btn btn-secondary w-100">🔄 রিসেট</a>
    </div>
  </form>

  <div class="print-area">
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <div class="card border-success shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title mb-0">মোট ভাড়া</h5>
            <p class="card-text fs-4 text-success">৳ <?= number_format($total_amount, 2) ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-primary shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title mb-0">মোট এন্ট্রি</h5>
            <p class="card-text fs-4 text-primary"><?= $total_entries ?> বার</p>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white">
        ভাড়া এন্ট্রি লিস্ট
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered table-striped mb-0">
          <thead class="table-dark">
            <tr>
              <th>নাম</th>
              <th>গাড়ির নাম্বার</th>
              <th>পরিমাণ</th>
              <th>তারিখ</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($entries): ?>
              <?php foreach ($entries as $entry): ?>
                <tr>
                  <td><?= htmlspecialchars($entry['payer_name']) ?></td>
                  <td><?= htmlspecialchars($entry['gari_no']) ?></td>
                  <td>৳ <?= number_format($entry['amount'], 2) ?></td>
                  <td><?= htmlspecialchars($entry['payment_date']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center">❌ কোনো ভাড়া এন্ট্রি পাওয়া যায়নি</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>