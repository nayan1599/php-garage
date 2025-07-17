<?php
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

$today = date('Y-m-d');
// আজকের ভাড়া
$stmtToday = $pdo->prepare("SELECT r.rental_id, c.first_name, c.last_name, g.gari_nam, g.model, r.start_date, r.end_date 
    FROM rentals r 
    JOIN customer c ON r.customer_id = c.customer_id 
    JOIN gari g ON r.gari_id = g.gari_id 
    WHERE :today BETWEEN r.start_date AND r.end_date
    LIMIT 25");
$stmtToday->execute([':today' => $today]);
$todayRentals = $stmtToday->fetchAll();

// বকেয়া ভাড়া
$stmtDue = $pdo->prepare("SELECT r.rental_id, c.first_name, c.last_name, g.gari_nam, g.model, r.end_date 
    FROM rentals r 
    JOIN customer c ON r.customer_id = c.customer_id 
    JOIN gari g ON r.gari_id = g.gari_id 
    WHERE r.end_date < :today AND r.status = 'Active'
    LIMIT 25");
$stmtDue->execute([':today' => $today]);
$dueRentals = $stmtDue->fetchAll();
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>ভাড়া ড্যাশবোর্ড</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .dashboard-title {
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .rental-card {
            border-left: 5px solid #28a745;
            transition: 0.3s;
        }
        .rental-card:hover {
            background: #e9f7ef;
        }
        .due-card {
            border-left: 5px solid #dc3545;
            transition: 0.3s;
        }
        .due-card:hover {
            background: #fcebea;
        }
        .count-badge {
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="container mt-4">

    <h2 class="dashboard-title">🚘 ভাড়া ড্যাশবোর্ড</h2>

    <div class="row">
        <!-- আজকের ভাড়া -->
        <div class="col-md-6">
            <h4 class="text-success mb-3">
                📅 আজকের ভাড়া 
                <span class="badge bg-success count-badge"><?= count($todayRentals) ?></span>
            </h4>
            <?php if ($todayRentals): ?>
                <?php foreach ($todayRentals as $r): ?>
                    <div class="card mb-3 rental-card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-1">
                                <i class="bi bi-person-circle"></i>
                                <?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?>
                            </h5>
                            <p class="mb-1">
                                <i class="bi bi-car-front-fill"></i>
                                <?= htmlspecialchars($r['gari_nam'] . ' - ' . $r['model']) ?>
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-calendar-range"></i>
                                <?= htmlspecialchars($r['start_date']) ?> → <?= htmlspecialchars($r['end_date']) ?>
                            </p>
                            <span class="badge bg-success">চলমান</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-light border">⚠️ আজ কোনো ভাড়া নেই</div>
            <?php endif; ?>
        </div>

        <!-- বকেয়া ভাড়া -->
        <div class="col-md-6">
            <h4 class="text-danger mb-3">
                ⏰ বকেয়া ভাড়া 
                <span class="badge bg-danger count-badge"><?= count($dueRentals) ?></span>
            </h4>
            <?php if ($dueRentals): ?>
                <?php foreach ($dueRentals as $r): ?>
                    <div class="card mb-3 due-card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-1">
                                <i class="bi bi-person-circle"></i>
                                <?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?>
                            </h5>
                            <p class="mb-1">
                                <i class="bi bi-car-front-fill"></i>
                                <?= htmlspecialchars($r['gari_nam'] . ' - ' . $r['model']) ?>
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-calendar-x"></i>
                                শেষ তারিখ: <?= htmlspecialchars($r['end_date']) ?>
                            </p>
                            <span class="badge bg-danger">বকেয়া</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-light border">🎉 কোনো বকেয়া ভাড়া নেই</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
