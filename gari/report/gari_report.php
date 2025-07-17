<?php
 
 
// ফিল্টার শর্ত
$where = "1";
$params = [];

if (!empty($_GET['status'])) {
    $where .= " AND avastha = ?";
    $params[] = $_GET['status'];
}

if (!empty($_GET['search'])) {
    $where .= " AND (gari_nam LIKE ? OR registration_number LIKE ?)";
    $params[] = "%" . $_GET['search'] . "%";
    $params[] = "%" . $_GET['search'] . "%";
}

// গাড়ির তালিকা
$stmt = $pdo->prepare("SELECT * FROM gari WHERE $where ORDER BY gari_id DESC");
$stmt->execute($params);
$garis = $stmt->fetchAll();

// মোট গাড়ির সংখ্যা ও মোট ভাড়া

?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>গাড়ির রিপোর্ট</title>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="container mt-4">
    <h2 class="mb-3">📄 গাড়ির রিপোর্ট</h2>

    <form class="row g-2 mb-3 no-print" method="get" action="index.php?page=gari/report/gari_report">
        <input type="hidden" name="page" value="gari/report/gari_report"> <!-- পেজ প্যারামিটার রাখতে হবে -->
        <div class="col-md-3">
            <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="form-control" placeholder="গাড়ির নাম বা রেজিস্ট্রেশন নাম্বার">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">সব স্ট্যাটাস</option>
                <option value="Available" <?= (($_GET['status'] ?? '') == 'Available') ? 'selected' : '' ?>>উপলব্ধ</option>
                <option value="Rented" <?= (($_GET['status'] ?? '') == 'Rented') ? 'selected' : '' ?>>ভাড়ায়</option>
                <option value="Sold" <?= (($_GET['status'] ?? '') == 'Sold') ? 'selected' : '' ?>>বিক্রি হয়েছে</option>
                <option value="Installment" <?= (($_GET['status'] ?? '') == 'Installment') ? 'selected' : '' ?>>কিস্তিতে</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔍 সার্চ</button>
        </div>
        <div class="col-md-2">
            <button type="button" onclick="window.print()" class="btn btn-secondary w-100">🖨️ প্রিন্ট</button>
        </div>
        <div class="col-md-2">
            <a href="index.php?page=gari/report/gari_report" class="btn btn-outline-danger w-100">❌ রিসেট</a>
        </div>
    </form>
    <hr>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>নাম</th>
                    <th>মডেল</th>
                    <th>নাম্বার প্লেট</th>
                    <th>গাড়ির ধরন</th>
                    <th>জ্বালানি</th>
                    <th>গিয়ার</th>
                    <th>ভাড়া (দৈনিক)</th>
                    <th>স্ট্যাটাস</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($garis): foreach ($garis as $gari):

                        // print_r($gari); // Debugging line to check the structure of $gari
                ?>
                        <tr>
                            <td><?= $gari['gari_id'] ?></td>
                            <td><?= htmlspecialchars($gari['gari_nam']) ?></td>
                            <td><?= htmlspecialchars($gari['model']) ?></td>
                            <td><?= htmlspecialchars($gari['registration_number']) ?></td>
                            <td><?= htmlspecialchars($gari['gari_dhoron']) ?></td>
                            <td><?= htmlspecialchars($gari['fuel_type']) ?></td>
                            <td><?= htmlspecialchars($gari['transmission_type']) ?></td>
                            <td><?= number_format($gari['vara_dhor'], 2) ?>৳</td>
                            <td><?= $gari['avastha'] ?></td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">⚠️ কোনো গাড়ি পাওয়া যায়নি।</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
</body>

</html>