<?php


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ড্রপডাউন লিস্টের জন্য ডেটা আনা
$customers = $conn->query("
    SELECT customer_id, first_name, last_name 
    FROM customer 
    WHERE customer_type = 'Renter' AND status = 'Active'
")->fetchAll(PDO::FETCH_ASSOC);

    $cars = $conn->query("SELECT gari_id, gari_nam, registration_number FROM gari WHERE avastha = 'Rented'")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("কানেকশন ব্যর্থ: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="bn">
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">🚗 নতুন ভাড়া রেকর্ড করুন</h4>
            </div>
            <div class="card-body">
                <form action="index.php?page=rented/insert_rented" method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">কাস্টমার ID:</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">-- সিলেক্ট করুন --</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?= $c['customer_id'] ?>"><?= $c['first_name'] . ' ' . $c['last_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">গাড়ি ID:</label>
                        <select name="gari_id" class="form-select" required>
                            <option value="">-- সিলেক্ট করুন --</option>
                            <?php foreach ($cars as $g): ?>
                                <option value="<?= $g['gari_id'] ?>"><?= $g['gari_nam'] . ' (' . $g['registration_number'] . ')' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">ভাড়ার ধরন:</label>
                        <select name="rental_type" class="form-select">
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Monthly">Monthly</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">ভাড়ার পরিমাণ:</label>
                        <input type="number" step="0.01" name="rent_amount" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">শুরুর তারিখ:</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">শেষ তারিখ:</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">নিরাপত্তা অর্থ:</label>
                        <input type="number" step="0.01" name="security_deposit" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">মোট প্রাপ্য:</label>
                        <input type="number" step="0.01" name="total_due" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">পরিশোধিত পরিমাণ:</label>
                        <input type="number" step="0.01" name="total_paid" value="0.00" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">পেমেন্ট অবস্থা:</label>
                        <select name="payment_status" class="form-select">
                            <option value="Due">Due</option>
                            <option value="Partial">Partial</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">স্ট্যাটাস:</label>
                        <select name="status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">মন্তব্য:</label>
                        <textarea name="notes" rows="4" class="form-control" placeholder="অতিরিক্ত কিছু লিখতে চাইলে..."></textarea>
                    </div>

                    <div class="col-12 text-end">

                        <button type="submit" class="btn btn-success">💾 সংরক্ষণ করুন</button>
                        <a href="index.php?page=rented/index" class="btn btn-secondary">🔙 ফিরে যান</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

 

</body>

</html>