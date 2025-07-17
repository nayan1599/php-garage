<?php
 

// ফিল্টার শর্ত
$where = "1";
$params = [];

if (!empty($_GET['search'])) {
    $where .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR g.gari_nam LIKE ?)";
    $params[] = "%" . $_GET['search'] . "%";
    $params[] = "%" . $_GET['search'] . "%";
    $params[] = "%" . $_GET['search'] . "%";
}

if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $where .= " AND i.start_date BETWEEN ? AND ?";
    $params[] = $_GET['from_date'];
    $params[] = $_GET['to_date'];
}

// installment রেকর্ড
$stmt = $pdo->prepare("SELECT i.*, c.first_name, c.last_name, g.gari_nam 
                       FROM installment_sales i 
                       JOIN customer c ON i.customer_id = c.customer_id 
                       JOIN gari g ON i.gari_id = g.gari_id 
                       WHERE $where 
                       ORDER BY i.sale_id DESC");
$stmt->execute($params);
$sales = $stmt->fetchAll();

$total_sales = count($sales);
$total_due = 0;
$total_paid = 0;
foreach ($sales as $sale) {
    $total_due += $sale['installment_amount'];
    $total_paid += $sale['paid_amount'];
}
$total_remaining = $total_due - $total_paid;
?>

<!DOCTYPE html>
<html lang="bn">
<head>
 
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-summary {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="container mt-4">
    <h2 class="text-center bg-dark text-white p-2 rounded">📄 কিস্তি বিক্রয় রিপোর্ট</h2>

    <form class="row g-2 mb-4 no-print bg-light p-3 rounded" method="get" action="index.php?page=kisti_form/report/installment_report">
        <input type="hidden" name="page" value="kisti_form/report/installment_report">
        <div class="col-md-3">
            <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="form-control" placeholder="কাস্টমার বা গাড়ির নাম">
        </div>
        <div class="col-md-2">
            <input type="date" name="from_date" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="date" name="to_date" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔍 সার্চ</button>
        </div>
        <div class="col-md-2">
            <a href="index.php?page=kisti_form/report/installment_report" class="btn btn-secondary w-100">❌ রিসেট</a>
        </div>
        <div class="col-md-1">
            <button type="button" onclick="window.print()" class="btn btn-success w-100">🖨️</button>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary card-summary">
                <div class="card-body text-center">
                    <h5>মোট বিক্রয়</h5>
                    <p class="fs-3"><?= $total_sales ?> টি</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success card-summary">
                <div class="card-body text-center">
                    <h5>মোট কিস্তি</h5>
                    <p class="fs-3"><?= number_format($total_due, 2) ?>৳</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning card-summary">
                <div class="card-body text-center">
                    <h5>অবশিষ্ট কিস্তি</h5>
                    <p class="fs-3"><?= number_format($total_remaining, 2) ?>৳</p>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Sale ID</th>
                <th>কাস্টমার</th>
                <th>গাড়ি</th>
                <th>তারিখ</th>
                <th>মোট কিস্তি</th>
                <th>প্রদত্ত কিস্তি</th>
                <th>অবশিষ্ট</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($sales): foreach ($sales as $sale): ?>
            <tr>
                <td><?= $sale['sale_id'] ?></td>
                <td><?= htmlspecialchars($sale['first_name'] . ' ' . $sale['last_name']) ?></td>
                <td><?= htmlspecialchars($sale['gari_nam']) ?></td>
                <td><?= htmlspecialchars($sale['sale_date']) ?></td>
                <td><?= number_format($sale['installment_amount'], 2) ?>৳</td>
                <td><?= number_format($sale['paid_amount'], 2) ?>৳</td>
                <td><?= number_format($sale['installment_amount'] - $sale['paid_amount'], 2) ?>৳</td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="7" class="text-center text-danger">⚠️ কোনো কিস্তি বিক্রয় পাওয়া যায়নি।</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
