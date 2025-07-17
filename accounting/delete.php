<?php
// ডাটাবেজ সংযোগ
 include './config/db.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// id চেক
if (empty($_GET['id'])) {
    die("⚠️ কোনো এন্ট্রি নির্বাচন করা হয়নি!");
}

$id = (int)$_GET['id'];

// ডিলিট কনফার্মেশন
if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
    try {
        $stmt = $conn->prepare("DELETE FROM accounting_entries WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo "<script>alert('✅ এন্ট্রি সফলভাবে মুছে ফেলা হয়েছে'); window.location.href='index.php?page=accounting/index';</script>";
        exit;
    } catch (PDOException $e) {
        echo "❌ ত্রুটি: " . $e->getMessage();
    }
}

// ডেটা দেখানোর জন্য
$stmt = $conn->prepare("SELECT * FROM accounting_entries WHERE id = :id");
$stmt->execute([':id' => $id]);
$entry = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$entry) {
    die("⚠️ এই আইডি এর কোনো এন্ট্রি নেই!");
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>অ্যাকাউন্টিং এন্ট্রি ডিলিট</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="card p-4 shadow-sm">
        <h4 class="text-danger">⚠️ আপনি কি নিশ্চিতভাবে এই এন্ট্রি মুছতে চান?</h4>
        <p><strong>তারিখ:</strong> <?= htmlspecialchars($entry['entry_date']) ?></p>
        <p><strong>বিবরণ:</strong> <?= htmlspecialchars($entry['description']) ?></p>
        <p><strong>ধরন:</strong> <?= htmlspecialchars($entry['type']) ?></p>
        <p><strong>টাকা:</strong> <?= htmlspecialchars($entry['amount']) ?></p>

        <form method="post">
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="btn btn-danger">🗑️ হ্যাঁ, মুছে ফেলুন</button>
            <a href="index.php?page=accounting/index" class="btn btn-secondary">🔙 বাতিল করুন</a>
        </form>
    </div>
</div>
</body>
</html>
