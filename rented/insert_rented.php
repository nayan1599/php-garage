<?php


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগ ব্যর্থ: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $sql = "INSERT INTO rentals (
                    customer_id, gari_id, rental_type, rent_amount,
                    start_date, end_date, security_deposit, total_due,
                    total_paid, payment_status, status, notes
                ) VALUES (
                    :customer_id, :gari_id, :rental_type, :rent_amount,
                    :start_date, :end_date, :security_deposit, :total_due,
                    :total_paid, :payment_status, :status, :notes
                )";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':customer_id'      => $_POST['customer_id'],
            ':gari_id'          => $_POST['gari_id'],
            ':rental_type'      => $_POST['rental_type'],
            ':rent_amount'      => $_POST['rent_amount'],
            ':start_date'       => $_POST['start_date'],
            ':end_date'         => $_POST['end_date'] ?: null,
            ':security_deposit' => $_POST['security_deposit'] ?? 0.00,
            ':total_due'        => $_POST['total_due'] ?? null,
            ':total_paid'       => $_POST['total_paid'] ?? 0.00,
            ':payment_status'   => $_POST['payment_status'],
            ':status'           => $_POST['status'],
            ':notes'            => $_POST['notes'] ?? null
        ]);

        echo "<script>alert('✅ ভাড়া সফলভাবে রেকর্ড হয়েছে'); window.location.href='index.php?page=rented/index';</script>";
    } catch (PDOException $e) {
        echo "❌ ত্রুটি: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
