<?php
include '../config/db.php'; // নিশ্চিত করুন এই ফাইলটি ডাটাবেস কানেকশন করে
include '../config/session_check.php';  // সেশন চেক
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

if (!isset($_GET['customer_id'])) {
    die("কাস্টমার আইডি প্রদান করা হয়নি।");
}

$customer_id = $_GET['customer_id'];

// ফর্ম সাবমিট চেক
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $national_id = $_POST['national_id'];
    $occupation = $_POST['occupation'];
    $customer_type = $_POST['customer_type'];
    $notes = $_POST['notes'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE customer SET
        first_name = ?, last_name = ?, gender = ?, date_of_birth = ?, phone = ?, email = ?,
        address = ?, city = ?, state = ?, postal_code = ?, country = ?, national_id = ?,
        occupation = ?, customer_type = ?, notes = ?, status = ?
        WHERE customer_id = ?");
    $stmt->execute([
        $first_name, $last_name, $gender, $date_of_birth, $phone, $email,
        $address, $city, $state, $postal_code, $country, $national_id,
        $occupation, $customer_type, $notes, $status, $customer_id
    ]);

    echo "<div class='alert alert-success'>✅ তথ্য সফলভাবে আপডেট হয়েছে!</div>";
     echo "<script>alert('✅ কাস্টমার সফলভাবে যোগ হয়েছে'); window.location.href='index.php?page=customer/index';</script>";
}

// বর্তমান তথ্য লোড
$stmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();

if (!$customer) {
    die("কাস্টমার খুঁজে পাওয়া যায়নি।");
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>✏️ কাস্টমার এডিট</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>✏️ কাস্টমার এডিট</h2>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">প্রথম নাম</label>
            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($customer['first_name']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">শেষ নাম</label>
            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($customer['last_name']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">লিঙ্গ</label>
            <select name="gender" class="form-select">
                <option value="">নির্বাচন করুন</option>
                <option <?= $customer['gender']=='Male' ? 'selected' : '' ?>>Male</option>
                <option <?= $customer['gender']=='Female' ? 'selected' : '' ?>>Female</option>
                <option <?= $customer['gender']=='Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">জন্ম তারিখ</label>
            <input type="date" name="date_of_birth" class="form-control" value="<?= $customer['date_of_birth'] ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">ফোন</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">ইমেইল</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">জাতীয় পরিচয়পত্র</label>
            <input type="text" name="national_id" class="form-control" value="<?= htmlspecialchars($customer['national_id']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">শহর</label>
            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($customer['city']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">রাজ্য</label>
            <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($customer['state']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">পোস্টাল কোড</label>
            <input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($customer['postal_code']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">দেশ</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($customer['country']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">পেশা</label>
            <input type="text" name="occupation" class="form-control" value="<?= htmlspecialchars($customer['occupation']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">কাস্টমার টাইপ</label>
            <select name="customer_type" class="form-select" required>
                <option <?= $customer['customer_type']=='Buyer' ? 'selected' : '' ?>>Buyer</option>
                <option <?= $customer['customer_type']=='Seller' ? 'selected' : '' ?>>Seller</option>
                <option <?= $customer['customer_type']=='Renter' ? 'selected' : '' ?>>Renter</option>
                <option <?= $customer['customer_type']=='Installment' ? 'selected' : '' ?>>Installment</option>
                <option <?= $customer['customer_type']=='All' ? 'selected' : '' ?>>All</option>
            </select>
        </div>
        <div class="col-md-12">
            <label class="form-label">ঠিকানা</label>
            <textarea name="address" class="form-control"><?= htmlspecialchars($customer['address']) ?></textarea>
        </div>
        <div class="col-md-12">
            <label class="form-label">নোট</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($customer['notes']) ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label">স্ট্যাটাস</label>
            <select name="status" class="form-select" required>
                <option value="Active" <?= $customer['status']=='Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $customer['status']=='Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">💾 সেভ করুন</button>
            <a href="index.php?page=customer/index" class="btn btn-secondary">🔙 ব্যাক</a>
        </div>
    </form>
</body>
</html>
