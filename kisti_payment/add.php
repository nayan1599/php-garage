<?php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // installment_sales list with next_due_date
    $sales = $pdo->query("
        SELECT s.installment_id, s.sale_id, s.next_due_date, 
               c.first_name, c.last_name, 
               g.gari_nam, g.registration_number
        FROM installment_sales s
        JOIN customer c ON s.customer_id = c.customer_id
        JOIN gari g ON s.gari_id = g.gari_id
        ORDER BY s.installment_id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>কিস্তি পেমেন্ট এন্ট্রি</title>
    <link href="../assets/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h4 class="mb-4 text-center">➕ নতুন কিস্তি পেমেন্ট এন্ট্রি</h4>
    <form action="index.php?page=kisti_payment/post_payment" method="POST" class="row g-3">

        <div class="col-md-6">
            <label for="installment_id" class="form-label">কিস্তি বিক্রয় নির্বাচন করুন</label>
            <select name="installment_id" id="installment_id" class="form-select" required>
                <option value="">-- নির্বাচন করুন --</option>
                <?php foreach ($sales as $s): ?>
                    <option 
                        value="<?= $s['installment_id'] ?>" 
                        data-next-date="<?= $s['next_due_date'] ?>">
                        <?= "ID:{$s['installment_id']} - {$s['first_name']} {$s['last_name']} - {$s['gari_nam']} - {$s['registration_number']}" ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="payment_date" class="form-label">পেমেন্টের তারিখ</label>
            <input type="date" name="payment_date" id="payment_date" class="form-control" required value="<?= date('Y-m-d') ?>">
        </div>

        <div class="col-md-6">
            <label for="amount" class="form-label">পরিমাণ (টাকা)</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="payment_method" class="form-label">পেমেন্ট পদ্ধতি</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="Cash">Cash</option>
                <option value="Bank">Bank</option>
                <option value="Mobile Banking">Mobile Banking</option>
                <option value="Card">Card</option>
                <option value="Other">Other</option>
            </select>
        </div>

        

        <div class="col-md-6">
            <label for="late_fee" class="form-label">বিলম্ব ফি</label>
            <input type="number" step="0.01" name="late_fee" id="late_fee" class="form-control" value="0.00" readonly>
        </div>

        <div class="col-md-6">
            <label for="paid_for_month" class="form-label">যে মাসের জন্য</label>
            <input type="month" name="paid_for_month" id="paid_for_month" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="status" class="form-label">অবস্থা</label>
            <select name="status" id="status" class="form-select" required>
                <option value="Paid">Paid</option>
                <option value="Partial">Partial</option>
                <option value="Advance">Advance</option>
            </select>
        </div>

        <div class="col-12">
            <label for="note" class="form-label">নোট</label>
            <textarea name="note" id="note" class="form-control"></textarea>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-success">💾 পেমেন্ট সংরক্ষণ করুন</button>
        </div>
    </form>
</div>

<!-- ✅ JavaScript Logic -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const installmentSelect = document.getElementById('installment_id');
    const paidForMonthInput = document.getElementById('paid_for_month');
    const lateFeeInput = document.getElementById('late_fee');

    let nextDueDate = null;

    installmentSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        nextDueDate = selectedOption.getAttribute('data-next-date');
        paidForMonthInput.value = nextDueDate ? nextDueDate.slice(0, 7) : '';
        lateFeeInput.value = "0.00";
    });

    paidForMonthInput.addEventListener('change', function () {
        if (!nextDueDate) return;

        const selectedMonth = this.value;            // eg: 2025-07
        const dueMonth = nextDueDate.slice(0, 7);    // eg: 2025-07

        lateFeeInput.value = (selectedMonth !== dueMonth) ? "100.00" : "0.00";
    });
});
</script>
</body>
</html>
