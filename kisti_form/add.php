<?php
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $customers = $conn->query("SELECT customer_id, first_name, last_name FROM customer WHERE customer_type = 'Installment' AND status = 'Active' AND customer_id NOT IN (SELECT customer_id FROM installment_sales)")->fetchAll(PDO::FETCH_ASSOC);

  $cars = $conn->query("SELECT gari_id, gari_nam, registration_number FROM gari WHERE avastha = 'Installment' AND gari_id NOT IN (SELECT gari_id FROM installment_sales)")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("কানেকশন ব্যর্থ: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>কিস্তিতে বিক্রি</title>
  <link href="../assets/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h3 class="mb-4">🚗 কিস্তিতে বিক্রি ফর্ম</h3>
  <form action="index.php?page=kisti_form/post_sale" method="POST" class="row g-3">

    <!-- কাস্টমার নির্বাচন -->
    <div class="col-md-6">
      <label for="customer_id" class="form-label">কাস্টমার নির্বাচন করুন</label>
      <select name="customer_id" id="customer_id" class="form-select" required>
        <option value="">-- নির্বাচন করুন --</option>
        <?php foreach ($customers as $c): ?>
          <option value="<?= htmlspecialchars($c['customer_id']) ?>">
            <?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- গাড়ি নির্বাচন -->
    <div class="col-md-6">
      <label for="gari_id" class="form-label">গাড়ি নির্বাচন করুন</label>
      <select name="gari_id" id="gari_id" class="form-select" required>
        <option value="">-- নির্বাচন করুন --</option>
        <?php foreach ($cars as $g): ?>
          <option value="<?= htmlspecialchars($g['gari_id']) ?>">
            <?= htmlspecialchars($g['gari_nam']) ?> (<?= htmlspecialchars($g['registration_number']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- দাম, কিস্তি, সুদ -->
    <div class="col-md-6">
      <label for="total_price" class="form-label">মোট দাম</label>
      <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label for="down_payment" class="form-label">এডভান্স পেমেন্ট</label>
      <input type="number" step="0.01" name="down_payment" id="down_payment" class="form-control" value="0.00" required>
    </div>

    <div class="col-md-6">
      <label for="total_installments" class="form-label">মোট কিস্তি সংখ্যা</label>
      <input type="number" name="total_installments" id="total_installments" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label for="installment_amount" class="form-label">প্রতি কিস্তির পরিমাণ</label>
      <input type="number" step="0.01" name="installment_amount" id="installment_amount" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label for="interest_rate" class="form-label">সুদের হার (%)</label>
      <input type="number" step="0.01" name="interest_rate" id="interest_rate" class="form-control" value="0.00">
    </div>

    <div class="col-md-6">
      <label for="start_date" class="form-label">শুরুর তারিখ</label>
      <input type="date" name="start_date" id="start_date" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label for="next_due_date" class="form-label">পরবর্তী কিস্তির তারিখ</label>
      <input type="date" name="next_due_date" id="next_due_date" class="form-control" readonly>
    </div>

 <div class="col-md-6">
  <label for="penalty_start_after_days" class="form-label">কত দিন পরে জরিমানা প্রযোজ্য</label>
  <input type="number" name="penalty_start_after_days" id="penalty_start_after_days" class="form-control" value="5" required>
</div>

<div class="col-md-6">
  <label for="penalty_amount_fixed" class="form-label">জরিমানার নির্দিষ্ট পরিমাণ</label>
  <input type="number" step="0.01" name="penalty_amount_fixed" id="penalty_amount_fixed" class="form-control" value="200.00" required>
</div>


    <div class="col-md-6">
      <label for="last_payment_date" class="form-label">শেষ কিস্তির তারিখ (অটো)</label>
      <input type="date" name="last_payment_date" id="last_payment_date" class="form-control" readonly>
    </div>

    <div class="col-12">
      <label for="remarks" class="form-label">মন্তব্য</label>
      <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-primary">💾 সংরক্ষণ করুন</button>
    </div>
  </form>
</div>

<!-- ✅ JavaScript Logic -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const totalPriceField = document.getElementById('total_price');
  const downPaymentField = document.getElementById('down_payment');
  const totalInstallmentsField = document.getElementById('total_installments');
  const installmentAmountField = document.getElementById('installment_amount');
  const startDateField = document.getElementById('start_date');
  const nextDueDateField = document.getElementById('next_due_date');
  const lastPaymentField = document.getElementById('last_payment_date');

  function calculateInstallmentAmount() {
    const totalPrice = parseFloat(totalPriceField.value) || 0;
    const downPayment = parseFloat(downPaymentField.value) || 0;
    const totalInstallments = parseInt(totalInstallmentsField.value) || 0;

    if (totalInstallments > 0) {
      const remaining = totalPrice - downPayment;
      const perInstallment = remaining / totalInstallments;
      installmentAmountField.value = perInstallment.toFixed(2);
    } else {
      installmentAmountField.value = '';
    }
  }

  function calculateNextDueDate() {
    const startDate = new Date(startDateField.value);
    if (!isNaN(startDate)) {
      const nextDate = new Date(startDate);
      nextDate.setMonth(nextDate.getMonth() + 1);
      const yyyy = nextDate.getFullYear();
      const mm = String(nextDate.getMonth() + 1).padStart(2, '0');
      const dd = String(nextDate.getDate()).padStart(2, '0');
      nextDueDateField.value = `${yyyy}-${mm}-${dd}`;
    }
    calculateLastPaymentDate();
  }

  function calculateLastPaymentDate() {
    const startDate = new Date(startDateField.value);
    const totalInstallments = parseInt(totalInstallmentsField.value) || 0;
    if (!isNaN(startDate) && totalInstallments > 0) {
      const lastDate = new Date(startDate);
      lastDate.setMonth(lastDate.getMonth() + totalInstallments);
      const yyyy = lastDate.getFullYear();
      const mm = String(lastDate.getMonth() + 1).padStart(2, '0');
      const dd = String(lastDate.getDate()).padStart(2, '0');
      lastPaymentField.value = `${yyyy}-${mm}-${dd}`;
    } else {
      lastPaymentField.value = '';
    }
  }

  totalPriceField.addEventListener('input', calculateInstallmentAmount);
  downPaymentField.addEventListener('input', calculateInstallmentAmount);
  totalInstallmentsField.addEventListener('input', function () {
    calculateInstallmentAmount();
    calculateLastPaymentDate();
  });
  startDateField.addEventListener('change', function () {
    calculateNextDueDate();
    calculateLastPaymentDate();
  });
});
</script>
</body>
</html>
