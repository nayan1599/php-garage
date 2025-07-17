<?php


// মোট গাড়ি
$totalStmt = $pdo->query("SELECT COUNT(*) as total FROM gari");
$totalGari = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

// স্ট্যাটাস অনুসারে সংখ্যা
$statusStmt = $pdo->query("SELECT avastha, COUNT(*) as count FROM gari GROUP BY avastha");
$statusCounts = $statusStmt->fetchAll(PDO::FETCH_ASSOC);

// সাম্প্রতিক ৫টি গাড়ি
$recentStmt = $pdo->query("SELECT gari_id, gari_nam, registration_number, avastha FROM gari ORDER BY gari_id DESC LIMIT 5");
$recentGaris = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>🚗 গাড়ি ড্যাশবোর্ড</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

    <h1 class="mb-4">🚗 গাড়ি ড্যাশবোর্ড</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary">
                <div class="card-body text-center">
                    <h5 class="card-title">মোট গাড়ি</h5>
                    <h2><?= $totalGari ?></h2>
                </div>
            </div>
        </div>

 <?php 
$statusMap = [
    'available' => 'উপলব্ধ',
    'rented' => 'ভাড়া দেওয়া হয়েছে',
    'sold' => 'বিক্রি হয়েছে',
    'installment' => 'কিস্তিতে বিক্রি'
];
?>

<?php foreach ($statusCounts as $status): ?>
    <div class="col-md-4 my-2">
        <div class="card text-bg-secondary">
            <div class="card-body text-center">
                <h5 class="card-title">
                    <?php
                        $statusKey = strtolower($status['avastha']);
                        echo $statusMap[$statusKey] ?? htmlspecialchars($status['avastha']);
                    ?>
                </h5>
                <h3><?= $status['count'] ?></h3>
            </div>
        </div>
    </div>
<?php endforeach; ?>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            সাম্প্রতিক ৫টি গাড়ি
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>নাম</th>
                        <th>নিবন্ধন নং</th>
                        <th>স্ট্যাটাস</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($recentGaris) > 0): ?>
                        <?php foreach ($recentGaris as $gari): ?>
                            <tr>
                                <td><?= $gari['gari_id'] ?></td>
                                <td><?= htmlspecialchars($gari['gari_nam']) ?></td>
                                <td><?= htmlspecialchars($gari['registration_number']) ?></td>
                                <td>
                                    <?php
                                    $status = htmlspecialchars($gari['avastha']);
                                    if ($status === 'Available') {
                                        echo 'উপলব্ধ';
                                    } elseif ($status === 'Rented') {
                                        echo 'ভাড়া দেওয়া হয়েছে';
                                    } elseif ($status === 'Sold') {
                                        echo 'বিক্রি হয়েছে';
                                    } elseif ($status === 'Installment') {
                                        echo 'কিস্তিতে বিক্রি';
                                    } else {
                                        echo $status; // ডিফল্ট হিসেবে যেটা আছে সেটাই দেখাবে
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="index.php?page=gari/view&gari_id=<?= $gari['gari_id'] ?>" class="btn btn-sm btn-info">👁️ দেখুন</a>
                                    <a href="index.php?page=gari/edit&gari_id=<?= $gari['gari_id'] ?>" class="btn btn-sm btn-warning">✏️ সম্পাদনা</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">⚠️ কোনো গাড়ি পাওয়া যায়নি।</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-end">
        <a href="index.php?page=gari/add" class="btn btn-success">➕ নতুন গাড়ি যোগ করুন</a>
    </div>

</body>

</html>