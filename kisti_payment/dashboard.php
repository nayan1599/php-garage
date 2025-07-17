<?php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $today = date('Y-m-d');
    $startOfMonth = date('Y-m-01');
    $endOfMonth = date('Y-m-t');

    // আজকের মোট পেমেন্ট
    $stmt = $pdo->prepare("SELECT SUM(amount) AS total_paid FROM installment_payments WHERE payment_date = ?");
    $stmt->execute([$today]);
    $total_paid = $stmt->fetch()['total_paid'] ?? 0;

    // আজকের পেমেন্ট লিস্ট
    $stmt = $pdo->prepare("
        SELECT p.*, c.first_name, c.last_name, g.gari_nam 
        FROM installment_payments p
        JOIN customer c ON p.customer_id = c.customer_id
        JOIN gari g ON p.gari_id = g.gari_id
        WHERE p.payment_date = ?
        ORDER BY p.payment_id DESC
    ");
    $stmt->execute([$today]);
    $today_payments = $stmt->fetchAll();

    // মোট বকেয়া (price - down_payment)
    $stmt = $pdo->query("SELECT SUM(total_price - down_payment) AS total_due FROM installment_sales");
    $total_due = $stmt->fetch()['total_due'] ?? 0;

    // বর্তমান বকেয়া
    $current_due = $total_due - $total_paid;

    // এই মাসে যাদের কিস্তি আছে, কিন্তু যাদের চলতি মাসের পেমেন্ট হয়েছে তারা বাদ দেয়া হয়েছে
    $stmt = $pdo->prepare("
        SELECT s.*, c.first_name, c.last_name, g.gari_nam 
        FROM installment_sales s
        JOIN customer c ON s.customer_id = c.customer_id
        JOIN gari g ON s.gari_id = g.gari_id
        WHERE s.next_due_date BETWEEN ? AND ?
          AND s.installment_id NOT IN (
              SELECT DISTINCT installment_id 
              FROM installment_payments 
              WHERE paid_for_month = DATE_FORMAT(?, '%Y-%m')
          )
        ORDER BY s.next_due_date ASC
    ");
    $stmt->execute([$startOfMonth, $endOfMonth, $today]);
    $this_month_dues = $stmt->fetchAll();

} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>কিস্তি পেমেন্ট ড্যাশবোর্ড</title>
    <link href="../assets/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 0.4rem 1.2rem rgba(0, 0, 0, 0.1);
        }
        .table-container {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            box-shadow: 0 0.3rem 1rem rgba(0, 0, 0, 0.05);
        }
        .fs-4 {
            font-weight: bold;
        }
        .section-title {
            font-weight: 600;
            margin-bottom: 1rem;
            border-bottom: 2px solid #ddd;
            padding-bottom: 0.5rem;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">💼 কিস্তি পেমেন্ট ড্যাশবোর্ড</h2>
        <p class="text-muted">রিপোর্ট তারিখ: <?= $today ?></p>
    </div>

    <!-- Cards Summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-12">
            <div class="card bg-success text-white text-center">
                <div class="card-body">
                    <h5>আজকের মোট আদায়</h5>
                    <p class="fs-4">৳ <?= number_format($total_paid, 2) ?></p>
                </div>
            </div>
        </div>
 
   
    </div>

    <!-- আজকের কিস্তি লিস্ট -->
    <div class="table-container">
        <h4 class="section-title">📅 আজকের কিস্তি পেমেন্ট</h4>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>ক্রেতার নাম</th>
                    <th>গাড়ির নাম</th>
                    <th>টাকা (৳)</th>
                    <th>পেমেন্ট তারিখ</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($today_payments): foreach($today_payments as $p): ?>
                    <tr>
                        <td><?= $p['payment_id'] ?></td>
                        <td><?= htmlspecialchars($p['first_name'].' '.$p['last_name']) ?></td>
                        <td><?= htmlspecialchars($p['gari_nam']) ?></td>
                        <td><?= number_format($p['amount'],2) ?></td>
                        <td><?= htmlspecialchars($p['payment_date']) ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">⚠️ আজ কোনো কিস্তি পেমেন্ট নেই।</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- এই মাসের কিস্তি -->
    <div class="table-container">
        <h4 class="section-title">🗓️ এই মাসে যাদের কিস্তি আছে</h4>
        <table class="table table-bordered table-hover">
            <thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>ক্রেতার নাম</th>
                    <th>গাড়ির নাম</th>
                    <th>পরবর্তী কিস্তির তারিখ</th>
                    <th>বাকি টাকা (৳)</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($this_month_dues): foreach($this_month_dues as $i => $due): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($due['first_name'].' '.$due['last_name']) ?></td>
                        <td><?= htmlspecialchars($due['gari_nam']) ?></td>
                        <td><?= htmlspecialchars($due['next_due_date']) ?></td>
                        <td>
                            <?php
                            $remaining = $due['total_price'] - $due['down_payment'];
                            echo '৳ ' . number_format($remaining, 2);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">⚠️ এই মাসে কোনো কিস্তির সময়সীমা নেই।</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
