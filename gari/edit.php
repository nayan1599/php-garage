<?php
 

if (!isset($_GET['gari_id'])) {
    die("❌ গাড়ির ID নির্ধারিত নয়।");
}

$gari_id = $_GET['gari_id'];

// গাড়ির বর্তমান তথ্য লোড
$stmt = $pdo->prepare("SELECT * FROM gari WHERE gari_id = ?");
$stmt->execute([$gari_id]);
$gari = $stmt->fetch();

if (!$gari) {
    die("❌ নির্দিষ্ট গাড়ি খুঁজে পাওয়া যায়নি।");
}

// ফর্ম সাবমিট হলে আপডেট
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['gari_nam'];
    $model = $_POST['model'];
    $plate = $_POST['registration_number'];
    $color = $_POST['color'];
    $status = $_POST['status'];



    $update = $pdo->prepare("UPDATE gari SET gari_nam=?, model=?, registration_number=?, color=?, status=? WHERE gari_id=?");
    if ($update->execute([$name, $model, $plate, $color, $status, $gari_id])) {
        echo "<script>alert('✔️ গাড়ির তথ্য আপডেট হয়েছে'); window.location='index.php?page=gari/dashboard';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>❌ আপডেট ব্যর্থ হয়েছে।</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>✏️ গাড়ি এডিট</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <h3 class="mb-4">✏️ গাড়ির তথ্য এডিট করুন</h3>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">গাড়ির নাম</label>
                <input type="text" name="gari_nam" class="form-control" value="<?= htmlspecialchars($gari['gari_nam']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">মডেল</label>
                <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($gari['model']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">নাম্বার প্লেট</label>
                <input type="text" name="registration_number" class="form-control" value="<?= htmlspecialchars($gari['registration_number']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">রঙ</label>
                <input type="color" name="color" class="form-control" value="<?= htmlspecialchars($gari['color']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">স্ট্যাটাস</label>
                <select name="status" class="form-select">
                    <option value="Available" <?= $gari['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                    <option value="Rented" <?= $gari['status'] == 'Rented' ? 'selected' : '' ?>>Rented</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">💾 সংরক্ষণ</button>
            <a href="index.php?page=gari/index" class="btn btn-secondary">↩️ ফিরে যান</a>
        </form>
    </div>
</body>

</html>