<?php
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

$where = "1=1";
$params = [];

if (!empty($_GET['search'])) {
    $where = "(first_name LIKE :search OR last_name LIKE :search OR phone LIKE :search)";
    $params = [':search' => '%' . $_GET['search'] . '%'];
}

$sql = "SELECT * FROM customer WHERE $where ORDER BY customer_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$customers = $stmt->fetchAll();

?>



<body class="container mt-4">
    <h2 class="text-center">👥 কাস্টমার তালিকা</h2>
<hr>
    <div class="row mb-3">
        <div class="col-md-8">
            <form class="row g-2 mb-3" method="get" action="index.php">
                <!-- অবশ্যই action index.php দেওয়া হবে -->
                <input type="hidden" name="page" value="customer/index"> <!-- পেজ প্যারামিটার রাখতে হবে -->

                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="নাম বা মোবাইল দিয়ে খুঁজুন">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">🔍 খুঁজুন</button>
                </div>
                <div class="col-md-2">
                    <a href="index.php?page=customer/index" class="btn btn-outline-danger w-100">❌ রিসেট</a>
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="index.php?page=customer/add" class="btn btn-success">➕ নতুন কাস্টমার যোগ করুন</a>
        </div>
    </div>
 
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>নাম</th>
                <th>মোবাইল</th>
                <th>টাইপ</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($customers)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">⚠️ কোনো কাস্টমার পাওয়া যায়নি।</td>
                </tr>
            <?php else: ?>
                <?php foreach ($customers as $cus): ?>
                    <tr>
                        <td><?= $cus['customer_id'] ?></td>
                        <td><?= htmlspecialchars($cus['first_name'] . ' ' . $cus['last_name']) ?></td>
                        <td><?= htmlspecialchars($cus['phone']) ?></td>
                        <td><?= htmlspecialchars($cus['customer_type']) ?></td>
                        <td class="text-end">
                            <a href="index.php?page=customer/view&customer_id=<?= $cus['customer_id'] ?>" class="btn btn-sm btn-info">👁️ View</a>
                            <a href="index.php?page=customer/edit&customer_id=<?= $cus['customer_id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                            <a href="index.php?page=customer/delete&customer_id=<?= $cus['customer_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('আপনি কি নিশ্চিত ডিলিট করতে চান?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>