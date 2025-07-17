<?php
 
// সার্চ টার্ম ক্যাপচার
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "SELECT * FROM gari";
if ($search !== '') {
    $query .= " WHERE gari_nam LIKE :search OR model LIKE :search OR registration_number LIKE :search";
}
$query .= " ORDER BY gari_id DESC";

$stmt = $pdo->prepare($query);
if ($search !== '') {
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt->execute();
}
?>



<!DOCTYPE html>
<html lang="bn">
 

<body class="container py-4">
    <h2 class="mb-4">🚗 গাড়ির তালিকা</h2>

    <div class="mb-3">

    </div>
    <hr>
    <!-- সার্চ ফর্ম -->
    <div class="row">
        <div class="col-md-8">
            <div class="col-md-12">
                <form class="row mb-3" method="get" action="index.php">
                     <input type="hidden" name="page" value="gari/index"> <!-- পেজ প্যারামিটার রাখতে হবে -->
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="নাম, মডেল বা নাম্বার প্লেট লিখুন" value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">🔍 খুঁজুন</button>
                    </div>
                    <div class="col-md-2">
                        <a href="index.php?page=gari/index" class="btn btn-secondary w-100">♻️ রিসেট</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3 text-end">
                <a href="./report/gari_report.php" class="btn btn-outline-secondary">📊 রিপোর্ট</a>

                <a href="index.php?page=gari/add" class="btn btn-success">➕ নতুন গাড়ি যোগ করুন</a>
            </div>
        </div>
    </div>





    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success">✅ গাড়ি সফলভাবে ডিলিট করা হয়েছে।</div>
    <?php endif; ?>


    <!-- টেবিল -->
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>নাম</th>
                <th>মডেল</th>
                <th>নাম্বার প্লেট</th>
                <th>গাড়ির ধরন</th>
                <th>অবস্থা</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($stmt->rowCount() > 0):
                while ($row = $stmt->fetch()): ?>
                    <tr>
                        <td><?= $row['gari_id'] ?></td>
                        <td><?= htmlspecialchars($row['gari_nam']) ?></td>
                        <td><?= htmlspecialchars($row['model']) ?></td>
                        <td><?= htmlspecialchars($row['registration_number']) ?></td>
                        <td><?= htmlspecialchars($row['gari_dhoron']) ?></td>
                        <td>
                            <?php if ($row['avastha'] == 'Available'): ?>
                                <span class="badge bg-success">উপলব্ধ</span>
                            <?php elseif ($row['avastha'] == 'Rented'): ?>
                                <span class="badge bg-warning">ভাড়ায়</span>
                            <?php elseif ($row['avastha'] == 'Sold'): ?>
                                <span class="badge bg-danger">বিক্রি</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($row['avastha']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="index.php?page=gari/view&gari_id=<?= $row['gari_id'] ?>" class="btn btn-sm btn-info">👁️ দেখুন</a>
                            <a href="index.php?page=gari/edit&gari_id=<?= $row['gari_id'] ?>" class="btn btn-sm btn-warning">✏️ এডিট</a>
                            <a href="index.php?page=gari/delete&gari_id=<?= $row['gari_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('আপনি কি ডিলিট করতে চান?')">🗑️ ডিলিট</a>




                        </td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">❗ কোনো গাড়ি পাওয়া যায়নি।</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>