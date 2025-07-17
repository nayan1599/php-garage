<?php
include '../config.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

$today = date('Y-m-d');

// মোট আজকের আয়
$totalIncomeToday = $conn->query("SELECT SUM(amount) FROM accounting_entries WHERE type = 'Income' AND entry_date = '$today'")->fetchColumn();

// মোট আজকের ব্যয়
$totalExpenseToday = $conn->query("SELECT SUM(amount) FROM accounting_entries WHERE type = 'Expense' AND entry_date = '$today'")->fetchColumn();

// সার্চ ও ফিল্টার
$where = "WHERE 1";
$params = [];
if (!empty($_GET['search'])) {
    $where .= " AND (description LIKE :search)";
    $params[':search'] = '%' . $_GET['search'] . '%';
}
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $where .= " AND entry_date BETWEEN :from_date AND :to_date";
    $params[':from_date'] = $_GET['from_date'];
    $params[':to_date'] = $_GET['to_date'];
}

// পেজিনেশন
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$countStmt = $conn->prepare("SELECT COUNT(*) FROM accounting_entries $where");
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

$sql = "SELECT * FROM accounting_entries $where ORDER BY entry_date DESC LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>অ্যাকাউন্টিং এন্ট্রির তালিকা</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">

    <h3 class="mb-3">📒 অ্যাকাউন্টিং এন্ট্রির তালিকা</h3>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">মোট আয়</h5>
                    <p class="card-text fs-4"><?= number_format($totalIncomeToday ?? 0, 2) ?> টাকা</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">মোট ব্যয়</h5>
                    <p class="card-text fs-4"><?= number_format($totalExpenseToday ?? 0, 2) ?> টাকা</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ফিল্টার ফর্ম -->
    <form class="row g-2 mb-3" method="get" action="index.php?page=accounting/index">
        <input type="hidden" name="page" value="accounting/index">
        <div class="col-md-3">
            <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="form-control" placeholder="বিবরণ লিখুন...">
        </div>
        <div class="col-md-2">
            <input type="date" name="from_date" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="date" name="to_date" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">🔍 খুঁজুন</button>
        </div>
        <div class="col-md-2">
            <a href="index.php?page=accounting/index" class="btn btn-secondary">রিসেট</a>
        </div>
    </form>

<div class="mb-3 text-end">
        <a href="index.php?page=accounting/add_accounting" class="btn btn-success">➕ নতুন এন্ট্রি যোগ করুন</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>আইডি</th>
                <th>তারিখ</th>
                <th>বিবরণ</th>
                <th>ধরন</th>
                <th>টাকা</th>
                <th>পেমেন্ট পদ্ধতি</th>
                <th>রেফারেন্স</th>
                <th>মন্তব্য</th>
                <th>একশন</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($entries) > 0): ?>
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td><?= $entry['id'] ?></td>
                        <td><?= $entry['entry_date'] ?></td>
                        <td><?= htmlspecialchars($entry['description']) ?></td>
                        <td><?= $entry['type'] == 'Income' ? 'আয়' : 'ব্যয়' ?></td>
                        <td><?= number_format($entry['amount'], 2) ?> টাকা</td>
                        <td><?= $entry['payment_method'] ?></td>
                        <td><?= $entry['reference_id'] ?></td>
                        <td><?= htmlspecialchars($entry['remarks']) ?></td>
                        <td>
                            <a href="index.php?page=accounting/edit&id=<?= $entry['id'] ?>" class="btn btn-sm btn-warning">✏️ এডিট</a>
                            <a href="index.php?page=accounting/delete&id=<?= $entry['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('আপনি কি নিশ্চিত মুছতে চান?')">🗑️ ডিলিট</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">⚠️ কোনো এন্ট্রি পাওয়া যায়নি!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- পেজিনেশন -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

</div>
</body>
</html>
