<?php

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// সার্চ শর্ত (যদি দরকার হয়)
$where = "1";
$params = [];

if (!empty($_GET['search'])) {
    $where .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR g.gari_nam LIKE ?)";
    $searchTerm = "%" . $_GET['search'] . "%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

// ডেটা লোড
$stmt = $pdo->prepare("SELECT r.*, c.first_name, c.last_name, g.gari_nam 
                       FROM rentals r
                       JOIN customer c ON r.customer_id = c.customer_id
                       JOIN gari g ON r.gari_id = g.gari_id
                       WHERE $where
                       ORDER BY r.rental_id DESC");
$stmt->execute($params);
$rentals = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>🚗 ভাড়া রিপোর্ট</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
        }
        .table-container {
            background: #fff;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        .badge {
            font-size: 0.85rem;
            padding: 0.35em 0.6em;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>
<body class="container py-4">

    <div class="mb-4 text-center">
        <h2 class="fw-bold">🚗 ভাড়া রিপোর্ট</h2>
        <p class="text-muted">সকল ভাড়া লেনদেনের বিস্তারিত তালিকা</p>
    </div>

    <form class="row g-2 mb-3" method="get" action="index.php?page=rented/report/rented_report">
        <input type="hidden" name="page" value="rented/report/rented_report">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control shadow-sm" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="কাস্টমার বা গাড়ির নাম">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 shadow-sm">🔍 সার্চ</button>
        </div>
        <div class="col-md-2">
            <a href="index.php?page=rented/report/rented_report" class="btn btn-outline-danger w-100 shadow-sm">❌ রিসেট</a>
        </div>
    </form>

    <div class="table-container">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>কাস্টমার</th>
                    <th>গাড়ি</th>
                    <th>ধরন</th>
                    <th>ভাড়া (৳)</th>
                    <th>শুরুর তারিখ</th>
                    <th>শেষ তারিখ</th>
                    <th>ডিপোজিট</th>
                    <th>মোট প্রাপ্য</th>
                    <th>মোট পরিশোধ</th>
                    <th>পেমেন্ট</th>
                    <th>অবস্থা</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rentals): foreach ($rentals as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></td>
                        <td><?= htmlspecialchars($r['gari_nam']) ?></td>
                        <td><?= htmlspecialchars($r['rental_type']) ?></td>
                        <td><?= number_format($r['rent_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($r['start_date']) ?></td>
                        <td><?= htmlspecialchars($r['end_date'] ?? 'N/A') ?></td>
                        <td><?= number_format($r['security_deposit'], 2) ?></td>
                        <td><?= number_format($r['total_due'], 2) ?></td>
                        <td><?= number_format($r['total_paid'], 2) ?></td>
                        <td>
                            <?php
                            $paymentMap = [
                                'Due' => ['label' => 'বাকি', 'class' => 'bg-danger'],
                                'Partial' => ['label' => 'আংশিক', 'class' => 'bg-warning text-dark'],
                                'Paid' => ['label' => 'পরিশোধিত', 'class' => 'bg-success']
                            ];
                            $pay = $paymentMap[$r['payment_status']] ?? ['label' => $r['payment_status'], 'class' => 'bg-secondary'];
                            ?>
                            <span class="badge <?= $pay['class'] ?>"><?= $pay['label'] ?></span>
                        </td>
                        <td>
                            <?php
                            $statusMap = [
                                'Active' => ['label' => 'চলমান', 'class' => 'bg-primary'],
                                'Completed' => ['label' => 'সম্পন্ন', 'class' => 'bg-success'],
                                'Cancelled' => ['label' => 'বাতিল', 'class' => 'bg-danger']
                            ];
                            $stat = $statusMap[$r['status']] ?? ['label' => $r['status'], 'class' => 'bg-secondary'];
                            ?>
                            <span class="badge <?= $stat['class'] ?>"><?= $stat['label'] ?></span>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="11" class="text-center text-muted">⚠️ কোনো ভাড়া রেকর্ড পাওয়া যায়নি।</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
