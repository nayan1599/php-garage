<?php
// ডাটাবেজ সংযোগ
include '../config/db.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// এডিটের জন্য আইডি চেক
if (empty($_GET['id'])) {
    die("⚠️ কোনো এন্ট্রি নির্বাচন করা হয়নি!");
}

$id = (int)$_GET['id'];

// ফর্ম সাবমিট হলে আপডেট
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entry_date = $_POST['entry_date'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $reference_id = !empty($_POST['reference_id']) ? $_POST['reference_id'] : null;
    $remarks = !empty($_POST['remarks']) ? $_POST['remarks'] : null;

    try {
        $sql = "UPDATE accounting_entries SET 
                    entry_date = :entry_date,
                    description = :description,
                    type = :type,
                    amount = :amount,
                    payment_method = :payment_method,
                    reference_id = :reference_id,
                    remarks = :remarks
                WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':entry_date' => $entry_date,
            ':description' => $description,
            ':type' => $type,
            ':amount' => $amount,
            ':payment_method' => $payment_method,
            ':reference_id' => $reference_id,
            ':remarks' => $remarks,
            ':id' => $id
        ]);
        echo "<script>alert('✅ এন্ট্রি সফলভাবে আপডেট হয়েছে'); window.location.href='index.php?page=accounting/index';</script>";
    } catch (PDOException $e) {
        echo "❌ ত্রুটি: " . $e->getMessage();
    }
}

// ডাটাবেজ থেকে এন্ট্রির ডাটা আনা
$stmt = $conn->prepare("SELECT * FROM accounting_entries WHERE id = :id");
$stmt->execute([':id' => $id]);
$entry = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$entry) {
    die("⚠️ এই আইডি এর কোনো এন্ট্রি নেই!");
}
?>


<body class="bg-light">
    <div class="container mt-4">
        <h3 class="mb-3">✏️ অ্যাকাউন্টিং এন্ট্রি এডিট</h3>

        <form method="post" class="card p-4 bg-white shadow-sm">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">তারিখ</label>
                    <input type="date" name="entry_date" value="<?= htmlspecialchars($entry['entry_date']) ?>" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">বিবরণ</label>
                    <input type="text" name="description" value="<?= htmlspecialchars($entry['description']) ?>" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">ধরন</label>
                    <select name="type" class="form-select" required>
                        <option value="Income" <?= $entry['type'] == 'Income' ? 'selected' : '' ?>>আয়</option>
                        <option value="Expense" <?= $entry['type'] == 'Expense' ? 'selected' : '' ?>>ব্যয়</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">টাকা</label>
                    <input type="number" step="0.01" name="amount" value="<?= htmlspecialchars($entry['amount']) ?>" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">পেমেন্ট পদ্ধতি</label>
                    <select name="payment_method" class="form-select" required>
                        <option <?= $entry['payment_method'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
                        <option <?= $entry['payment_method'] == 'Bank' ? 'selected' : '' ?>>Bank</option>
                        <option <?= $entry['payment_method'] == 'Mobile Banking' ? 'selected' : '' ?>>Mobile Banking</option>
                        <option <?= $entry['payment_method'] == 'Card' ? 'selected' : '' ?>>Card</option>
                        <option <?= $entry['payment_method'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">রেফারেন্স আইডি (ঐচ্ছিক)</label>
                    <input type="number" name="reference_id" value="<?= htmlspecialchars($entry['reference_id']) ?>" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">মন্তব্য</label>
                <textarea name="remarks" class="form-control"><?= htmlspecialchars($entry['remarks']) ?></textarea>
            </div>
<div class="d-flex justify-content-between">
    <button type="submit" class="btn btn-primary">💾 সংরক্ষণ করুন</button>
            <a href="index.php?page=accounting/index" class="btn btn-secondary">🔙 ফিরে যান</a>
</div>
            
        </form>
    </div>
</body>

</html>