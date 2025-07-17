<?php
include '../config/db.php'; // নিশ্চিত করুন এই ফাইলটি ডাটাবেস কানেকশন করে
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("⚠️ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

if (!isset($_GET['customer_id'])) {
    die("⚠️ কাস্টমার আইডি প্রদান করা হয়নি।");
}

$customer_id = $_GET['customer_id'];

// ডিলিট করার আগে চেক করুন কাস্টমার আছে কিনা
$stmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();

if (!$customer) {
    die("⚠️ কাস্টমার খুঁজে পাওয়া যায়নি।");
}

// ডিলিট করুন
$stmt = $pdo->prepare("DELETE FROM customer WHERE customer_id = ?");
$stmt->execute([$customer_id]);

echo "<div style='padding:20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb;'>
✅ কাস্টমার সফলভাবে ডিলিট হয়েছে।
</div>";

echo "<p><a href='index.php?page=customer/index'>🔙 কাস্টমার তালিকায় ফিরে যান</a></p>";
?>
