<?php
 
if (empty($_GET['customer_id'])) {
    die("⚠️ কাস্টমার আইডি দেওয়া হয়নি।");
}

$customer_id = (int) $_GET['customer_id'];

// কাস্টমার তথ্য
$stmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();

if (!$customer) {
    die("⚠️ কাস্টমার খুঁজে পাওয়া যায়নি।");
}

// কাস্টমারের সব rental
$stmt = $pdo->prepare("SELECT r.*, g.gari_nam FROM rentals r JOIN gari g ON r.gari_id = g.gari_id WHERE r.customer_id = ? ORDER BY r.start_date DESC");
$stmt->execute([$customer_id]);
$rentals = $stmt->fetchAll();

// কাস্টমারের rental পেমেন্ট story
$stmt = $pdo->prepare("SELECT p.*, r.rental_id FROM rental_payments p JOIN rentals r ON p.rental_id = r.rental_id WHERE r.customer_id = ? ORDER BY p.payment_date DESC");
$stmt->execute([$customer_id]);
$payments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>👁️ কাস্টমারের ভাড়া ও পেমেন্ট হিস্টোরি</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">
    <h2>👤 <?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?> এর ভাড়া ও পেমেন্ট হিস্টোরি</h2>


    <div class="mt-4">
        <h4>💰 পেমেন্ট হিস্টোরি</h4>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="card text-center p-3">
                    <h2> মোট ভাড়া </h2>
                    <p class="fs-4 text-success">
                        <?php
                        $total_rent = array_sum(array_column($rentals, 'total_due'));
                        echo number_format($total_rent, 2) . " ৳";
                        ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card text-center p-3">
                    <h2> মোট পেমেন্ট </h2>
                    <p class="fs-4 text-success">
                        <?php
                        $total_payment = array_sum(array_column($rentals, 'total_paid'));
                        echo number_format($total_payment, 2) . " ৳";
                        ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card text-center p-3">
                    <h2> মোট বাকি </h2>
                    <p class="fs-4 text-danger">
                        <?php
                        $remaining_due = $total_rent - $total_payment;
                        echo number_format($remaining_due, 2) . " ৳";
                        ?>
                    </p>
                </div>
            </div>


        </div>





        <h4 class="mt-4">🚗 ভাড়া তালিকা</h4>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ভাড়া ID</th>
                    <th>গাড়ির নাম</th>
                    <th>ধরন</th>
                    <th>শুরুর তারিখ</th>
                    <th>শেষ তারিখ</th>
                    <th>মোট ভাড়া (৳)</th>
                    <th>পরিশোধ (৳)</th>
                    <th>স্ট্যাটাস</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rentals): foreach ($rentals as $r): ?>
                        <tr>
                            <td><?= $r['rental_id'] ?></td>
                            <td><?= htmlspecialchars($r['gari_nam']) ?></td>
                            <td><?= htmlspecialchars($r['rental_type']) ?></td>
                            <td><?= htmlspecialchars($r['start_date']) ?></td>
                            <td><?= htmlspecialchars($r['end_date']) ?></td>
                            <td><?= number_format($r['total_due'], 2) ?></td>
                            <td><?= number_format($r['total_paid'], 2) ?></td>
                            <td><?= htmlspecialchars($r['status']) ?></td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">⚠️ কোনো ভাড়া রেকর্ড পাওয়া যায়নি।</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>



        <a href="index.php?page=rented/index" class="btn btn-secondary mt-3">🔙 ভাড়া তালিকায় ফিরুন</a>
</body>

</html>