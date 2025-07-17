<?php



// ফর্ম সাবমিট হলে
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // rental_id থেকে customer_id ও gari_id বের করুন
        $rentalStmt = $pdo->prepare("
            SELECT customer_id, gari_id 
            FROM rentals 
            WHERE rental_id = :rental_id
        ");
        $rentalStmt->execute([':rental_id' => $_POST['rental_id']]);
        $rentalData = $rentalStmt->fetch();

        if (!$rentalData) {
            throw new Exception("❌ Rental ID সঠিক নয় বা খুঁজে পাওয়া যায়নি।");
        }

        // Insert query
        $stmt = $pdo->prepare("
            INSERT INTO rental_payments 
            (rental_id, customer_id, gari_id, payment_date, amount_due, amount_paid, payment_method, late_fee, status, note) 
            VALUES 
            (:rental_id, :customer_id, :gari_id, :payment_date, :amount_due, :amount_paid, :payment_method, :late_fee, :status, :note)
        ");

        $stmt->execute([
            ':rental_id' => $_POST['rental_id'],
            ':customer_id' => $rentalData['customer_id'],
            ':gari_id' => $rentalData['gari_id'],
            ':payment_date' => $_POST['payment_date'],
            ':amount_due' => $_POST['amount_due'] ?? ($_POST['amount_paid'] + ($_POST['late_fee'] ?? 0.00)),
            ':amount_paid' => $_POST['amount_paid'],
            ':payment_method' => $_POST['payment_method'],
            ':late_fee' => $_POST['late_fee'] ?? 0.00,
            ':status' => $_POST['status'],
            ':note' => $_POST['note'] ?? null
        ]);

        echo "<script>alert('✅ পেমেন্ট সফলভাবে যোগ হয়েছে'); window.location.href='index.php?page=daily_payment/index';</script>";
    } catch (Exception $e) {
        echo "❌ ত্রুটি: " . $e->getMessage();
    }
}


$stmt = $pdo->query("
    SELECT r.rental_id, c.first_name, c.last_name
    FROM rentals r
    JOIN customer c ON r.customer_id = c.customer_id
      WHERE r.status = 'active'
    ORDER BY r.rental_id DESC
");
$rentals = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>➕ ভাড়া পেমেন্ট যোগ করুন</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">
    <h2>➕ ভাড়া পেমেন্ট যোগ করুন</h2>

    <form method="POST" class="row g-3">

        <div class="col-md-6">
            <label class="form-label">রেন্টাল নির্বাচন করুন</label>
            <select name="rental_id" class="form-select" required>
                <option value="">রেন্টাল নির্বাচন করুন</option>

                <!-- status  -->



                <?php foreach ($rentals as $r): ?>
                    <option value="<?= $r['rental_id'] ?>">
                        <?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?> |  (রেন্টাল আইডি: <?= $r['rental_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">পেমেন্টের তারিখ</label>
            <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">পরিশোধিত অর্থ (৳)</label>
            <input type="number" step="0.01" name="amount_paid" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">বিলম্ব ফি (৳)</label>
            <input type="number" step="0.01" name="late_fee" class="form-control" value="0.00">
        </div>

        <div class="col-md-6">
            <label class="form-label">পেমেন্ট পদ্ধতি</label>
            <select name="payment_method" class="form-select" required>
                <option value="Cash">নগদ</option>
                <option value="Bank">ব্যাংক</option>
                <option value="Mobile Banking">মোবাইল ব্যাংকিং</option>
                <option value="Card">কার্ড</option>
                <option value="Other">অন্যান্য</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">স্ট্যাটাস</label>
            <select name="status" class="form-select" required>
                <option value="Unpaid">অপরিশোধিত</option>
                <option value="Partial">আংশিক</option>
                <option value="Paid">পরিশোধিত</option>
                <option value="Late">বিলম্বিত</option>
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">মন্তব্য</label>
            <textarea name="note" class="form-control" rows="2"></textarea>
        </div>

        <!-- Hidden fields for customer_id, gari_id -->
        <input type="hidden" name="customer_id" id="customer_id_field">
        <input type="hidden" name="gari_id" id="gari_id_field">

        <div class="col-12">
            <button type="submit" class="btn btn-success">💾 সংরক্ষণ করুন</button>
            <a href="index.php?page=daily_payment/index" class="btn btn-secondary">📋 পেমেন্ট লিস্ট</a>
        </div>
    </form>

</body>

</html>