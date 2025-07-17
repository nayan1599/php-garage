<?php
// ডাটাবেজ সংযোগ
include './config/db.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

// ফিল্টার তারিখ
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

$where = "1"; // ডিফল্ট শর্ত

if ($start_date && $end_date) {
    $where = "entry_date BETWEEN :start_date AND :end_date";
} elseif ($start_date) {
    $where = "entry_date >= :start_date";
} elseif ($end_date) {
    $where = "entry_date <= :end_date";
}

// মোট আয় ও ব্যয় কুয়েরি প্রস্তুত
$query_income = "SELECT SUM(amount) FROM accounting_entries WHERE type = 'Income' AND $where";
$query_expense = "SELECT SUM(amount) FROM accounting_entries WHERE type = 'Expense' AND $where";

// Prepare statement
$stmt_income = $conn->prepare($query_income);
$stmt_expense = $conn->prepare($query_expense);

$params = [];
if ($start_date) $params[':start_date'] = $start_date;
if ($end_date) $params[':end_date'] = $end_date;

$stmt_income->execute($params);
$stmt_expense->execute($params);

$total_income = $stmt_income->fetchColumn() ?: 0;
$total_expense = $stmt_expense->fetchColumn() ?: 0;

// লেনদেন তালিকা
$query_entries = "SELECT * FROM accounting_entries WHERE $where ORDER BY entry_date DESC, id DESC";
$stmt_entries = $conn->prepare($query_entries);
$stmt_entries->execute($params);
$entries = $stmt_entries->fetchAll(PDO::FETCH_ASSOC);

// চার্টের জন্য মাস ভিত্তিক ডাটা (বর্তমান বছরের জন্য)
$current_year = date('Y');
$chart_income = array_fill(1, 12, 0);
$chart_expense = array_fill(1, 12, 0);

$sql_chart = "SELECT 
    MONTH(entry_date) as month, 
    type, 
    SUM(amount) as total 
FROM accounting_entries 
WHERE YEAR(entry_date) = :year 
GROUP BY month, type";

$stmt_chart = $conn->prepare($sql_chart);
$stmt_chart->execute([':year' => $current_year]);
$chart_data = $stmt_chart->fetchAll(PDO::FETCH_ASSOC);

foreach ($chart_data as $row) {
    $m = (int)$row['month'];
    if ($row['type'] === 'Income') {
        $chart_income[$m] = (float)$row['total'];
    } else {
        $chart_expense[$m] = (float)$row['total'];
    }
}

?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8" />
    <title>অ্যাকাউন্টিং রিপোর্ট</title>

    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3 class="mb-4">📊 অ্যাকাউন্টিং রিপোর্ট</h3>

    <!-- তারিখ ফিল্টার -->
   <form method="get" class="row g-3 mb-4" action="index.php?page=accounting/report/report_all">
        <input type="hidden" name="page" value="accounting/report/report_all" />
        <div class="col-auto">
            <label for="start_date" class="col-form-label">শুরু তারিখ</label>
            <input type="date" class="form-control" name="start_date" id="start_date" value="<?= htmlspecialchars($start_date) ?>" />
        </div>
        <div class="col-auto">
            <label for="end_date" class="col-form-label">শেষ তারিখ</label>
            <input type="date" class="form-control" name="end_date" id="end_date" value="<?= htmlspecialchars($end_date) ?>" />
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary">ফিল্টার করুন</button>
            <a href="index.php?page=accounting/report/report_all" class="btn btn-secondary">রিসেট</a>
        </div>
    </form>

    <!-- মোট আয় ও ব্যয় -->
    <div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">মোট আয়</h5>
                <p class="card-text fs-4">৳ <?= number_format($total_income, 2) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">মোট ব্যয়</h5>
                <p class="card-text fs-4">৳ <?= number_format($total_expense, 2) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">মোট ক্যাশ</h5>
                <p class="card-text fs-4">৳ <?= number_format($total_income - $total_expense, 2) ?></p>
            </div>
        </div>
    </div>
</div>


    <!-- চার্ট -->
    <h5>মাসিক আয় ও ব্যয়ের গ্রাফ (<?= $current_year ?>)</h5>
    <canvas id="incomeExpenseChart" style="max-width: 100%; height: 300px;"></canvas>

    <!-- লেনদেন তালিকা -->
    <h5 class="mt-4">🔹 সকল লেনদেন</h5>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>তারিখ</th>
                <th>বিবরণ</th>
                <th>ধরন</th>
                <th>টাকা</th>
                <th>পেমেন্ট পদ্ধতি</th>
                <th>মন্তব্য</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($entries): ?>
                <?php foreach ($entries as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['entry_date']) ?></td>
                        <td><?= htmlspecialchars($e['description']) ?></td>
                        <td><?= $e['type'] == 'Income' ? '<span class="text-success">আয়</span>' : '<span class="text-danger">ব্যয়</span>' ?></td>
                        <td>৳ <?= number_format($e['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($e['payment_method']) ?></td>
                        <td><?= htmlspecialchars($e['remarks']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">⚠️ কোনো লেনদেন নেই</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Print Button -->
    <button class="btn btn-outline-primary mt-3" onclick="window.print()">🖨️ প্রিন্ট করুন</button>

    <!-- Excel/PDF ডাউনলোড লিংক -->
    <div class="mt-3">
        <a href="export_excel.php?start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>" class="btn btn-success">📥 এক্সেল ডাউনলোড</a>
        <a href="export_pdf.php?start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>" class="btn btn-danger">📥 PDF ডাউনলোড</a>
    </div>
</div>

<script>
// চার্ট ডাটা জেনারেট
const labels = ['জানু', 'ফেব্রু', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
const incomeData = <?= json_encode(array_values($chart_income)) ?>;
const expenseData = <?= json_encode(array_values($chart_expense)) ?>;

const data = {
    labels: labels,
    datasets: [
        {
            label: 'আয়',
            data: incomeData,
            borderColor: 'green',
            backgroundColor: 'rgba(0,128,0,0.2)',
            fill: true,
            tension: 0.3
        },
        {
            label: 'ব্যয়',
            data: expenseData,
            borderColor: 'red',
            backgroundColor: 'rgba(255,0,0,0.2)',
            fill: true,
            tension: 0.3
        }
    ]
};

const config = {
    type: 'line',
    data: data,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            },
            title: {
                display: true,
                text: 'মাসিক আয় ও ব্যয়ের তুলনা'
            }
        }
    }
};

const incomeExpenseChart = new Chart(
    document.getElementById('incomeExpenseChart'),
    config
);
</script>

</body>
</html>
