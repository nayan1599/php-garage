<?php


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// রেন্টাল আইডি চেক
if (empty($_GET['id'])) {
    die("ভাড়া আইডি পাওয়া যায়নি।");
}
$id = (int) $_GET['id'];

// ফর্ম সাবমিট চেক
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rental_type = $_POST['rental_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'] ?: NULL;
    $total_due = $_POST['total_due'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE rentals SET rental_type = ?, start_date = ?, end_date = ?, total_due = ?, status = ? WHERE rental_id = ?");
    $stmt->execute([$rental_type, $start_date, $end_date, $total_due, $status, $id]);

    echo "<div class='alert alert-success'>✅ ভাড়া তথ্য সফলভাবে আপডেট হয়েছে।</div>";
}

// রেন্টাল তথ্য লোড
$stmt = $pdo->prepare("SELECT * FROM rentals WHERE rental_id = ?");
$stmt->execute([$id]);
$rental = $stmt->fetch();

if (!$rental) {
    die("ভাড়া তথ্য খুঁজে পাওয়া যায়নি।");
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>✏️ ভাড়া এডিট</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>✏️ ভাড়া এডিট (ID: <?= $rental['rental_id'] ?>)</h2>

    <form method="post" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">ভাড়া ধরন</label>
            <select name="rental_type" class="form-select" required>
                <option value="Daily" <?= $rental['rental_type'] == 'Daily' ? 'selected' : '' ?>>Daily</option>
                <option value="Monthly" <?= $rental['rental_type'] == 'Monthly' ? 'selected' : '' ?>>Monthly</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">শুরুর তারিখ</label>
            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($rental['start_date']) ?>" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">শেষ তারিখ</label>
            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($rental['end_date']) ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">মোট ভাড়া (৳)</label>
            <input type="number" step="0.01" name="total_due" class="form-control" value="<?= htmlspecialchars($rental['total_due']) ?>" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">স্ট্যাটাস</label>
            <select name="status" class="form-select" required>
                <option value="Active" <?= $rental['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Completed" <?= $rental['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option value="Cancelled" <?= $rental['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">💾 আপডেট করুন</button>
            <a href="index.php?page=rented/index" class="btn btn-secondary">🔙 তালিকায় ফেরত</a>
        </div>
    </form>
</body>
</html>
