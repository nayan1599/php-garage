<?php

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// চেক করা হচ্ছে rental_id পেয়েছি কিনা
if (empty($_GET['id'])) {
    die("⚠️ ভাড়া আইডি দেওয়া হয়নি।");
}

$id = (int)$_GET['id'];

// ডিলিট করার আগে নিশ্চিত করা যাক ডাটা আছে কিনা
$stmt = $pdo->prepare("SELECT * FROM rentals WHERE rental_id = ?");
$stmt->execute([$id]);
$rental = $stmt->fetch();

if (!$rental) {
    die("⚠️ রেকর্ড খুঁজে পাওয়া যায়নি।");
}

// ডিলিট করা হচ্ছে
$stmt = $pdo->prepare("DELETE FROM rentals WHERE rental_id = ?");
if ($stmt->execute([$id])) {
    header("Location: rental_list.php?msg=deleted");
    exit;
} else {
    echo "❌ ডিলিট করতে ব্যর্থ হয়েছে।";
}
?>
