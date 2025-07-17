<?php

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ কানেকশন ব্যর্থ: " . $e->getMessage());
}

// ডিফল্ট আজকের তারিখ
$date = $_GET['date'] ?? date('Y-m-d');

// ভাড়া এন্ট্রি লোড
$stmt = $conn->prepare("SELECT payer_name, gari_no, amount, payment_date FROM vara_payments WHERE payment_date = ? ORDER BY payment_id DESC");
$stmt->execute([$date]);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// মোট ভাড়া
$total_stmt = $conn->prepare("SELECT SUM(amount) FROM vara_payments WHERE payment_date = ?");
$total_stmt->execute([$date]);
$total = $total_stmt->fetchColumn();
$total = $total ?: 0;
?>


<style>
    @media print {
        .no-print {
            display: none;
        }
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .print-area,
        .print-area * {
            visibility: visible;
        }

        .print-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
    }
</style>

<body class="bg-light">
    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-success">📄 ভাড়া রিপোর্ট</h2>
            <button onclick="window.print()" class="btn btn-dark no-print">🖨️ প্রিন্ট</button>
        </div>

        <!-- ফিল্টার ফর্ম -->
        <form method="GET" class="row g-2 align-items-center mb-3 no-print" action="index.php">
            <input type="hidden" name="page" value="vara/report"> <!-- পেজ প্যারামিটার রাখতে হবে -->
            <div class="col-auto">
                <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">🔍 খুঁজুন</button>

                <a href="index.php?page=vara/report" class="btn btn-secondary">🔄 রিসেট</a>
            </div>
        </form>


        <div class="print-area">

            <div class="card shadow mb-3">
                <div class="card-body text-center">
                    <h5><?= date('d M Y', strtotime($date)) ?> এর মোট ভাড়া:
                        <span class="text-success">৳ <?= number_format($total, 2) ?></span>
                    </h5>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>নাম</th>
                                <th>গাড়ির নাম্বার</th>
                                <th>পরিমাণ</th>
                                <th>তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($entries): ?>
                                <?php foreach ($entries as $entry): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($entry['payer_name']) ?></td>
                                        <td><?= htmlspecialchars($entry['gari_no']) ?></td>
                                        <td>৳ <?= number_format($entry['amount'], 2) ?></td>
                                        <td><?= htmlspecialchars($entry['payment_date']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">❌ কোনো ভাড়া এন্ট্রি পাওয়া যায়নি</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>