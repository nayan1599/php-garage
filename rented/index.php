<?php


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// সার্চ শর্ত
$where = "1";
$params = [];

if (!empty($_GET['search'])) {
    $where .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR g.gari_nam LIKE ?)";
    $searchTerm = "%" . $_GET['search'] . "%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

// ভাড়া ডেটা লোড
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
    <title>🚗 ভাড়া তালিকা</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        .badge {
            font-size: 0.9rem;
            padding: 0.4em 0.7em;
        }
        .btn {
            padding: 0.3rem 0.6rem;
        }
    </style>
</head>
<body class="container py-4">

    <div class="mb-4 text-center">
        <h2 class="fw-bold">🚗 ভাড়া তালিকা</h2>
    </div>

    <form class="row g-2 mb-3" method="get" action="index.php?page=rented/index">
        <input type="hidden" name="page" value="rented/index">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control shadow-sm" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="কাস্টমার বা গাড়ির নাম">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 shadow-sm">🔍 সার্চ</button>
        </div>
        <div class="col-md-2">
            <a href="index.php?page=rented/index" class="btn btn-outline-danger w-100 shadow-sm">❌ রিসেট</a>
        </div>
    </form>

    <div class="mb-3 text-end">
        <a href="index.php?page=rented/add" class="btn btn-success shadow-sm">➕ নতুন ভাড়া যোগ করুন</a>
    </div>

    <?php if (!empty($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success shadow-sm">✅ রেন্টাল সফলভাবে ডিলিট হয়েছে।</div>
    <?php endif; ?>

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
                <th>স্ট্যাটাস</th>
                <th>অবস্থা</th>
                <th>অ্যাকশন</th>
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
                        $badgeClass = [
                            'Due' => 'bg-danger',
                            'Partial' => 'bg-warning text-dark',
                            'Paid' => 'bg-success'
                        ][$r['payment_status']] ?? 'bg-secondary';
                        ?>
                        <span class="badge <?= $badgeClass ?>">
                            <?= $r['payment_status'] === 'Due' ? 'বাকি' : ($r['payment_status'] === 'Partial' ? 'আংশিক' : ($r['payment_status'] === 'Paid' ? 'পরিশোধিত' : htmlspecialchars($r['payment_status']))) ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        $statusClass = [
                            'Active' => 'bg-primary',
                            'Completed' => 'bg-success',
                            'Cancelled' => 'bg-danger'
                        ][$r['status']] ?? 'bg-secondary';
                        ?>
                        <span class="badge <?= $statusClass ?>">
                            <?= $r['status'] === 'Active' ? 'চলমান' : ($r['status'] === 'Completed' ? 'সম্পন্ন' : ($r['status'] === 'Cancelled' ? 'বাতিল' : htmlspecialchars($r['status']))) ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=rented/view&customer_id=<?= $r['customer_id'] ?>" class="btn btn-sm btn-outline-info">👁️</a>
                        <a href="index.php?page=rented/edit&id=<?= $r['rental_id'] ?>" class="btn btn-sm btn-outline-warning">✏️</a>
                        <a href="index.php?page=rented/delete&id=<?= $r['rental_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত?')">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="12" class="text-center text-muted">⚠️ কোনো ভাড়া রেকর্ড পাওয়া যায়নি।</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
