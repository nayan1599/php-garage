<?php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $installment_id = $_POST['installment_id'];
        $payment_date   = $_POST['payment_date'];
        $amount         = $_POST['amount'];
        $payment_method = $_POST['payment_method'];
        $late_fee       = $_POST['late_fee'] ?: 0.00;
        $paid_for_month = $_POST['paid_for_month'] ?: null;
        $note           = $_POST['note'] ?: null;
        $status         = $_POST['status'];

        // installment_sale থেকে customer_id, gari_id, sale_id বের করুন
        $stmt = $pdo->prepare("SELECT customer_id, gari_id, sale_id FROM installment_sales WHERE installment_id = ?");
        $stmt->execute([$installment_id]);
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sale) {
            throw new Exception("Invalid installment ID");
        }

        // ১. কিস্তি পেমেন্ট ইনসার্ট করুন
        $sql = "INSERT INTO installment_payments (
            installment_id, customer_id, gari_id, sale_id,
            payment_date, amount, payment_method, late_fee,
            paid_for_month, note, status
        ) VALUES (
            :installment_id, :customer_id, :gari_id, :sale_id,
            :payment_date, :amount, :payment_method, :late_fee,
            :paid_for_month, :note, :status
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':installment_id' => $installment_id,
            ':customer_id'    => $sale['customer_id'],
            ':gari_id'        => $sale['gari_id'],
            ':sale_id'        => $sale['sale_id'],
            ':payment_date'   => $payment_date,
            ':amount'         => $amount,
            ':payment_method' => $payment_method,
            ':late_fee'       => $late_fee,
            ':paid_for_month' => $paid_for_month,
            ':note'           => $note,
            ':status'         => $status
        ]);

        // ২. next_due_date ১ মাস বাড়ান
        $update = $pdo->prepare("
            UPDATE installment_sales
            SET next_due_date = DATE_ADD(next_due_date, INTERVAL 1 MONTH)
            WHERE installment_id = ?
        ");
        $update->execute([$installment_id]);

        echo "<script>alert('✅ কিস্তি পেমেন্ট সফলভাবে সংরক্ষণ হয়েছে এবং পরবর্তী কিস্তির তারিখ আপডেট হয়েছে'); window.location.href='index.php?page=kisti_payment/index';</script>";
    } else {
        echo "Invalid request";
    }
} catch (Exception $e) {
    echo "❌ ত্রুটি: " . $e->getMessage();
}
