<?php
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // সর্বশেষ sale_id বের করুন
        $stmt = $conn->query("SELECT MAX(sale_id) AS last_sale_id FROM installment_sales");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $sale_id = ($row['last_sale_id'] ?? 0) + 1;

        // ডেটা সংগ্রহ
        $customer_id              = $_POST['customer_id'];
        $gari_id                  = $_POST['gari_id'];
        $total_price              = $_POST['total_price'];
        $down_payment             = $_POST['down_payment'] ?? 0.00;
        $total_installments       = $_POST['total_installments'];
        $installment_amount       = $_POST['installment_amount'];
        $interest_rate            = $_POST['interest_rate'] ?? 0.00;
        $start_date               = $_POST['start_date'];
        $next_due_date            = $_POST['next_due_date'] ?? null;
        $penalty_start_after_days = $_POST['penalty_start_after_days'] ?? 5;
        $penalty_amount_fixed     = $_POST['penalty_amount_fixed'] ?? 200.00;
        $last_payment_date        = $_POST['last_payment_date'] ?? null;
        $remarks                  = $_POST['remarks'] ?? null;

        // SQL ইনসার্ট
        $sql = "INSERT INTO installment_sales (
                    sale_id, customer_id, gari_id, total_price, down_payment,
                    total_installments, installment_amount, interest_rate,
                    start_date, next_due_date, last_payment_date,
                    penalty_start_after_days, penalty_amount_fixed,
                    payment_status, sale_status, remarks
                ) VALUES (
                    :sale_id, :customer_id, :gari_id, :total_price, :down_payment,
                    :total_installments, :installment_amount, :interest_rate,
                    :start_date, :next_due_date, :last_payment_date,
                    :penalty_start_after_days, :penalty_amount_fixed,
                    'Up-to-date', 'Running', :remarks
                )";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':sale_id'                  => $sale_id,
            ':customer_id'              => $customer_id,
            ':gari_id'                  => $gari_id,
            ':total_price'              => $total_price,
            ':down_payment'             => $down_payment,
            ':total_installments'       => $total_installments,
            ':installment_amount'       => $installment_amount,
            ':interest_rate'            => $interest_rate,
            ':start_date'               => $start_date,
            ':next_due_date'            => $next_due_date,
            ':last_payment_date'        => $last_payment_date,
            ':penalty_start_after_days' => $penalty_start_after_days,
            ':penalty_amount_fixed'     => $penalty_amount_fixed,
            ':remarks'                  => $remarks
        ]);

        // গাড়ির স্ট্যাটাস আপডেট
        $conn->query("UPDATE gari SET avastha = 'Installment' WHERE gari_id = $gari_id");

        echo "<script>alert('✅ কিস্তিতে বিক্রি সফলভাবে সংরক্ষণ হয়েছে'); window.location.href='index.php?page=kisti_form/index';</script>";
    } catch (PDOException $e) {
        echo "❌ ত্রুটি: " . $e->getMessage();
    }
} else {
    echo "Invalid Request!";
}
