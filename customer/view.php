<?php
 
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
    <title>👤 কাস্টমার ডিটেইলস</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">
    <h2>👤 কাস্টমার ডিটেইলস</h2>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td><?= $customer['customer_id'] ?></td>
        </tr>
        <tr>
            <th>নাম</th>
            <td><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></td>
        </tr>
        <tr>
            <th>লিঙ্গ</th>
            <td><?= htmlspecialchars($customer['gender']) ?></td>
        </tr>
        <tr>
            <th>জন্ম তারিখ</th>
            <td><?= htmlspecialchars($customer['date_of_birth']) ?></td>
        </tr>
        <tr>
            <th>ফোন</th>
            <td><?= htmlspecialchars($customer['phone']) ?></td>
        </tr>
        <tr>
            <th>ইমেইল</th>
            <td><?= htmlspecialchars($customer['email']) ?></td>
        </tr>
        <tr>
            <th>ঠিকানা</th>
            <td><?= nl2br(htmlspecialchars($customer['address'])) ?></td>
        </tr>
        <tr>
            <th>শহর</th>
            <td><?= htmlspecialchars($customer['city']) ?></td>
        </tr>
        <tr>
            <th>রাজ্য/বিভাগ</th>
            <td><?= htmlspecialchars($customer['state']) ?></td>
        </tr>
        <tr>
            <th>পোস্টাল কোড</th>
            <td><?= htmlspecialchars($customer['postal_code']) ?></td>
        </tr>
        <tr>
            <th>দেশ</th>
            <td><?= htmlspecialchars($customer['country']) ?></td>
        </tr>
        <tr>
            <th>জাতীয় পরিচয়পত্র</th>
            <td><?= htmlspecialchars($customer['national_id']) ?></td>
        </tr>
        <tr>
            <th>পেশা</th>
            <td><?= htmlspecialchars($customer['occupation']) ?></td>
        </tr>
        <tr>
            <th>কাস্টমার টাইপ</th>
            <td><?= htmlspecialchars($customer['customer_type']) ?></td>
        </tr>
        <tr>
            <th>রেজিস্ট্রেশনের তারিখ</th>
            <td><?= htmlspecialchars($customer['registration_date']) ?></td>
        </tr>
        <tr>
            <th>নোট</th>
            <td><?= nl2br(htmlspecialchars($customer['notes'])) ?></td>
        </tr>
        <tr>
            <th>স্ট্যাটাস</th>
            <td>
                <?php if ($customer['status'] == 'Active'): ?>
                    <span class="badge bg-success">Active</span>
                <?php else: ?>
                    <span class="badge bg-danger">Inactive</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <a href="index.php?page=customer/index" class="btn btn-secondary">🔙 ব্যাক</a>
</body>

</html>