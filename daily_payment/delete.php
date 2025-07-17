<?php
// DB সংযোগ
 include '../config/db.php'; // নিশ্চিত করুন এই ফাইলটি ডাটাবেস কানেকশন করে
 include '../config/session_check.php';  // সেশন চেক
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// পেমেন্ট আইডি যাচাই
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("⚠️ ভুল অনুরোধ! বৈধ পেমেন্ট আইডি দিন।");
}

$payment_id = (int)$_GET['id'];

try {
    // চেক করা পেমেন্ট আছে কিনা
    $stmt = $pdo->prepare("SELECT * FROM rental_payments WHERE payment_id = ?");
    $stmt->execute([$payment_id]);
    $payment = $stmt->fetch();

    if (!$payment) {
        die("⚠️ এই পেমেন্টটি খুঁজে পাওয়া যায়নি।");
    }

    // ডিলিট
    $delStmt = $pdo->prepare("DELETE FROM rental_payments WHERE payment_id = ?");
    $delStmt->execute([$payment_id]);

    echo "<script>alert('✅ পেমেন্ট সফলভাবে ডিলিট হয়েছে।'); window.location.href='rental_payment_list.php';</script>";
} catch (PDOException $e) {
    echo "❌ ত্রুটি: " . $e->getMessage();
}
?>
