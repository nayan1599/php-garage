<?php
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}



$today = date('Y-m-d');

// আজকের নতুন কাস্টমার সংখ্যা


$stmtCustomer = $pdo->prepare("SELECT COUNT(*) FROM customer WHERE DATE(registration_date) = :today");
$stmtCustomer->execute([':today' => $today]);
$newCustomers = $stmtCustomer->fetchColumn();

// আজকের নতুন গাড়ি সংখ্যা
$stmtGari = $pdo->prepare("SELECT COUNT(*) FROM gari WHERE DATE(jog_kora_tarikh) = :today");
$stmtGari->execute([':today' => $today]);
$newGari = $stmtGari->fetchColumn();

// আজকের নতুন ভাড়া সংখ্যা
$stmtRental = $pdo->prepare("SELECT COUNT(*) FROM rentals WHERE DATE(start_date) = :today");
$stmtRental->execute([':today' => $today]);
$newRentals = $stmtRental->fetchColumn();

// আজকের নতুন কিস্তি সংখ্যা
$stmtInstallment = $pdo->prepare("SELECT COUNT(*) FROM installment_payments WHERE DATE(payment_date) = :today");
$stmtInstallment->execute([':today' => $today]);
$newInstallments = $stmtInstallment->fetchColumn();

// আজকের মোট আয়
$stmtIncome = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM accounting_entries WHERE DATE(entry_date) = :today AND type = 'income'");
$stmtIncome->execute([':today' => $today]);
$todayIncome = $stmtIncome->fetchColumn();


// আজকের মোট ব্যয়
$stmtExpense = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM accounting_entries WHERE DATE(entry_date) = :today AND type = 'expense'");
$stmtExpense->execute([':today' => $today]);
$todayExpense = $stmtExpense->fetchColumn();    
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>আজকের ড্যাশবোর্ড</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .dashboard-title {
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .card-summary {
            transition: 0.3s;
            border-left: 5px solid #0d6efd;
        }
        .card-summary:hover {
            background: #f0f8ff;
        }
        .income-card {
            border-left: 5px solid #198754;
        }
        .income-card:hover {
            background: #e9f7ef;
        }
    </style>
</head>
<body class="container mt-4">
    <h2 class="dashboard-title">📊 আজকের ড্যাশবোর্ড</h2>
    <div class="row g-3">


    <!-- // -->
        
        

        <div class="col-md-4">
            <div class="card card-summary shadow-sm">
                <div class="card-body">
                    <h5><i class="bi bi-people-fill"></i> নতুন কাস্টমার</h5>
                    <h3><?= $newCustomers ?></h3>
                    <p class="text-muted">আজ যোগ হয়েছে</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-summary shadow-sm">
                <div class="card-body">
                    <h5><i class="bi bi-car-front-fill"></i> নতুন গাড়ি</h5>
                    <h3><?= $newGari ?></h3>
                    <p class="text-muted">আজ যোগ হয়েছে</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-summary shadow-sm">
                <div class="card-body">
                    <h5><i class="bi bi-truck-front"></i> নতুন ভাড়া</h5>
                    <h3><?= $newRentals ?></h3>
                    <p class="text-muted">আজ শুরু</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-summary shadow-sm">
                <div class="card-body">
                    <h5><i class="bi bi-credit-card-2-front-fill"></i> নতুন কিস্তি</h5>
                    <h3><?= $newInstallments ?></h3>
                    <p class="text-muted">আজ গ্রহণ</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card income-card shadow-sm">
                <div class="card-body">
                    <h5><i class="bi bi-cash-coin"></i> মোট আয়</h5>
                    <h3><?= number_format($todayIncome, 2) ?> ৳</h3>
                    <p class="text-muted">আজ</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card expense-card shadow-sm">
                <div class="card-body">
                    <h5><i class="bi bi-wallet2"></i> মোট ব্যয়</h5>
                    <h3><?= number_format($todayExpense, 2) ?> ৳</h3>
                    <p class="text-muted">আজ</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
