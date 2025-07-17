<?php
 
if (!isset($_GET['gari_id'])) {
    die("❌ গাড়ির ID নির্ধারিত নয়।");
}

$gari_id = $_GET['gari_id'];

// গাড়ির বর্তমান তথ্য লোড
$stmt = $pdo->prepare("SELECT * FROM gari WHERE gari_id = ?");
$stmt->execute([$gari_id]);
$gari = $stmt->fetch();

 
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>🚗 গাড়ি বিস্তারিত - <?= htmlspecialchars($gari['gari_nam']) ?></title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-4">

    <h1>🚗 গাড়ির বিস্তারিত</h1>
    <hr>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3><?= htmlspecialchars($gari['gari_nam']) ?></h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>গাড়ির নাম</th>
                        <td><?= htmlspecialchars($gari['gari_nam']) ?></td>
                    </tr>
                    <tr>
                        <th>নিবন্ধন নম্বর</th>
                        <td><?= htmlspecialchars($gari['registration_number']) ?></td>
                    </tr>
                    <tr>
                        <th>ব্র্যান্ড / মডেল</th>
                        <td><?= htmlspecialchars($gari['brand'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th>স্ট্যাটাস (অবস্থা)</th>
                        <td><?= htmlspecialchars($gari['avastha']) ?></td>
                    </tr>
                    <tr>
                        <th>দৈনিক ভাড়া (৳)</th>
                        <td><?= number_format($gari['daily_rent'] ?? 0, 2) ?></td>
                    </tr>
                    <tr>
                        <th>বর্ণনা</th>
                        <td><?= nl2br(htmlspecialchars($gari['description'] ?? 'কোনো বর্ণনা নেই')) ?></td>
                    </tr>
                    <tr>
                        <th>সৃষ্টি তারিখ</th>
                        <td><?= htmlspecialchars($gari['created_at'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th>সর্বশেষ আপডেট</th>
                        <td><?= htmlspecialchars($gari['updated_at'] ?? 'N/A') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="index.php?page=gari/index" class="btn btn-secondary">🔙 ফিরে যান</a>
     </div>

</body>
</html>
