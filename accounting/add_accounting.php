<?php
include '../config.php';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $entry_date = $_POST['entry_date'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $amount = $_POST['amount'];
        $payment_method = $_POST['payment_method'];
        $reference_id = !empty($_POST['reference_id']) ? $_POST['reference_id'] : null;
        $remarks = !empty($_POST['remarks']) ? $_POST['remarks'] : null;

        $sql = "INSERT INTO accounting_entries 
                (entry_date, description, type, amount, payment_method, reference_id, remarks) 
                VALUES 
                (:entry_date, :description, :type, :amount, :payment_method, :reference_id, :remarks)";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':entry_date' => $entry_date,
            ':description' => $description,
            ':type' => $type,
            ':amount' => $amount,
            ':payment_method' => $payment_method,
            ':reference_id' => $reference_id,
            ':remarks' => $remarks
        ]);

        echo "<script>alert('✅ এন্ট্রি সফলভাবে সংরক্ষণ হয়েছে'); window.location.href='index.php?page=accounting/index';</script>";
    } catch (PDOException $e) {
        echo "❌ ত্রুটি: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>📒 Accounting Entry Add</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
 
</head>
<body>
<div class="container ">
    <div class=" ">
        <h4 class="form-title">📒 নতুন অ্যাকাউন্টিং এন্ট্রি যোগ করুন</h4>
        <form method="POST">
<div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">🗓️ এন্ট্রি তারিখ</label>
                <input type="date" name="entry_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">📝 বিবরণ</label>
                <input type="text" name="description" class="form-control" placeholder="বিবরণ লিখুন">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">⚡ ধরন</label>
                <select name="type" class="form-select" required>
                    <option value="">-- সিলেক্ট করুন --</option>
                    <option value="Income">আয়</option>
                    <option value="Expense">ব্যয়</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">💰 পরিমাণ</label>
                <input type="number" name="amount" step="0.01" class="form-control" placeholder="৳" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">💳 পেমেন্ট পদ্ধতি</label>
                <select name="payment_method" class="form-select" required>
                    <option value="">-- সিলেক্ট করুন --</option>
                    <option value="Cash">নগদ</option>
                    <option value="Bank">ব্যাংক</option>
                    <option value="Mobile Banking">মোবাইল ব্যাংকিং</option>
                    <option value="Card">কার্ড</option>
                    <option value="Other">অন্যান্য</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">🔗 রেফারেন্স আইডি (যদি থাকে)</label>
                <input type="number" name="reference_id" class="form-control" placeholder="যদি থাকে">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">✏️ মন্তব্য</label>
                <textarea name="remarks" class="form-control" rows="3" placeholder="যা মন্তব্য করতে চান..."></textarea>
            </div>  

</div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">✅ সংরক্ষণ করুন</button>
            </div>
        </form>
    </div>
    

</div>


 
        </form>
    </div>
</div>
</body>
</html>
