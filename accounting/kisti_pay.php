<?php
include '../config/db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

$today = date('Y-m-d');

$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $installment_id = $_POST['installment_id'];
    $amount = $_POST['amount'];

 
    // Insert payment
    $stmt = $pdo->prepare("INSERT INTO installment_payments (installment_id, amount, payment_date) VALUES (:installment_id, :amount, CURDATE())");
    $stmt->execute([
        ':installment_id' => $installment_id,
        ':amount' => $amount
    ]);

    $payment_id = $pdo->lastInsertId();
    $note = 'Installment Payment ID: ' . $payment_id;

    // Check duplicate
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM accounting_entries WHERE note = :note AND entry_date = CURDATE()");
    $stmtCheck->execute([':note' => $note]);
    $exists = $stmtCheck->fetchColumn();

    if (!$exists) {
        $stmtAcc = $pdo->prepare("INSERT INTO accounting_entries (type, amount, entry_date, note) VALUES ('income', :amount, CURDATE(), :note)");
        $stmtAcc->execute([
            ':amount' => $amount,
            ':note' => $note
        ]);
    }

    $success = true;
    // ফর্ম খালি করার জন্য POST ডেটা ক্লিয়ার করো
    $_POST = [];
}
?>

<form method="POST" class="container mt-4" action="index.php?page=accounting/kisti_pay">
 <input type="hidden" name="page" value="accounting/kisti_pay">
    <?php if ($success): ?>
        <div class="alert alert-success">✅ কিস্তি পেমেন্ট সফল হয়েছে!</div>
    <?php endif; ?>

    <h3>কিস্তি পেমেন্ট</h3>
    <div class="mb-3">
        <label class="form-label">Installment ID</label>
        <input type="number" name="installment_id" class="form-control" required value="<?= htmlspecialchars($_POST['installment_id'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control" required value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>">
    </div>
    <button type="submit" class="btn btn-success">Pay Now</button>
</form>
