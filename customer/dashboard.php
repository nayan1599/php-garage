<?php
 

 
// সাম্প্রতিক ৫ জন কাস্টমার
$recentStmt = $pdo->query("SELECT customer_id, first_name, last_name, phone, customer_type FROM customer ORDER BY customer_id DESC LIMIT 5");
$recentCustomers = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

// মোট কাস্টমার সংখ্যা
$totalStmt = $pdo->query("SELECT COUNT(*) as total FROM customer");
$totalCustomers = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

// কাস্টমার টাইপ অনুসারে গ্রুপ করে সংখ্যা
$typeStmt = $pdo->query("SELECT customer_type, COUNT(*) as count FROM customer GROUP BY customer_type");
$typeCounts = $typeStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bn">

 

<body class="container mt-4">

<div class="d-flex">
    <div class="me-auto">
        <h1 class="mb-4"> কাস্টমার ড্যাশবোর্ড</h1>
    </div>
    <div>
        <a href="index.php?page=customer/add" class="btn btn-success mb-3">➕  নতুন কাস্টমার যোগ করুন</a>
    </div>
</div>


 
    <div class="row g-4 mb-4">

        <!-- মোট কাস্টমার -->
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-user-check fa-3x me-3"></i>
                    <div>
                        <h5 class="card-title">মোট কাস্টমার</h5>
                        <p class="display-4 mb-0"><?= $totalCustomers ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- কাস্টমার টাইপ অনুসারে সংখ্যা -->
        <div class="col-md-8">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center bg-secondary text-white">
                    <i class="fa-solid fa-list-ul me-2"></i>
                    <h5 class="mb-0">কাস্টমার টাইপ অনুসারে সংখ্যা</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($typeCounts as $type): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">

                        <span class="text-primary me-2 fa-solid "> 🏷️ </span> 

                        
                            <?= htmlspecialchars($type['customer_type']) ?>
                            <span class="badge bg-secondary rounded-pill"><?= $type['count'] ?></span>
                        </li>
                    <?php endforeach; ?>
                    <?php if (empty($typeCounts)): ?>
                        <li class="list-group-item text-center text-muted">কোনো তথ্য নেই।</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- সাম্প্রতিক ৫ জন কাস্টমার -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex align-items-center bg-dark text-white">
            <span class=" me-2 fa-solid "> 👤 </span> 
            <h5 class="mb-0">সাম্প্রতিক ৫ জন কাস্টমার</h5>
        </div>
        <div class="table-responsive">
    <table class="table table-bordered mb-0 align-middle">
        <thead class="table-dark">
            <tr>
                <th>🔢 ID</th>
                <th>👤 নাম</th>
                <th>📞 মোবাইল</th>
                <th>🏷️ টাইপ</th>
                <th class="text-center">⚙️ অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($recentCustomers) > 0): ?>
                <?php foreach ($recentCustomers as $cus): ?>
                    <tr>
                        <td><?= $cus['customer_id'] ?></td>
                        <td><?= htmlspecialchars($cus['first_name'] . ' ' . $cus['last_name']) ?></td>
                        <td><?= htmlspecialchars($cus['phone']) ?></td>
                        <td><?= htmlspecialchars($cus['customer_type']) ?></td>
                        <td class="text-center">
                            <a href="index.php?page=customer/view&customer_id=<?= $cus['customer_id'] ?>" 
                               class="btn btn-sm btn-info me-1" title="দেখুন">
                               👁️ দেখুন
                            </a>
                            <a href="index.php?page=customer/edit&customer_id=<?= $cus['customer_id'] ?>" 
                               class="btn btn-sm btn-warning me-1" title="সম্পাদনা">
                               ✏️ সম্পাদনা
                            </a>
                            <a href="index.php?page=customer/delete&customer_id=<?= $cus['customer_id'] ?>" 
                               class="btn btn-sm btn-danger" title="ডিলিট" onclick="return confirm('আপনি কি নিশ্চিত?')">
                               🗑️ ডিলিট
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">⚠️ কোনো কাস্টমার পাওয়া যায়নি।</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
    </div>


 
