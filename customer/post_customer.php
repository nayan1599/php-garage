<?php
 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "ডাটাবেজ কানেকশন ব্যর্থ: " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // 🔍 প্রথমে ডুপ্লিকেট আছে কিনা চেক করুন
        $check_sql = "SELECT COUNT(*) FROM customer WHERE (phone = :phone OR national_id = :national_id)";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([
            ':phone' => $_POST['phone'],
            ':national_id' => $_POST['national_id'] ?? ''
        ]);
        $count = $check_stmt->fetchColumn();

        if ($count > 0) {
            echo "<script>alert('⚠️ এই কাস্টমার ইতিমধ্যেই বিদ্যমান (ফোন / ইমেইল / NID)'); window.location.href='index.php?page=customer/index';</script>";
        } else {
            // ✅ নতুন কাস্টমার ইনসার্ট করুন
            $sql = "INSERT INTO customer (
                        first_name, last_name, gender, date_of_birth, phone, email,
                        address, city, state, postal_code, country,
                        national_id, occupation, customer_type, notes, status
                    ) VALUES (
                        :first_name, :last_name, :gender, :date_of_birth, :phone, :email,
                        :address, :city, :state, :postal_code, :country,
                        :national_id, :occupation, :customer_type, :notes, :status
                    )";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':first_name'     => $_POST['first_name'],
                ':last_name'      => $_POST['last_name'],
                ':gender'         => $_POST['gender'] ?? null,
                ':date_of_birth'  => $_POST['date_of_birth'] ?? null,
                ':phone'          => $_POST['phone'],
                ':email'          => $_POST['email'] ?? null,
                ':address'        => $_POST['address'] ?? null,
                ':city'           => $_POST['city'] ?? null,
                ':state'          => $_POST['state'] ?? null,
                ':postal_code'    => $_POST['postal_code'] ?? null,
                ':country'        => $_POST['country'] ?? null,
                ':national_id'    => $_POST['national_id'] ?? null,
                ':occupation'     => $_POST['occupation'] ?? null,
                ':customer_type'  => $_POST['customer_type'],
                ':notes'          => $_POST['notes'] ?? null,
                ':status'         => $_POST['status']
            ]);

            echo "<script>alert('✅ কাস্টমার সফলভাবে যোগ হয়েছে'); window.location.href='index.php?page=customer/index';</script>";
        }
    } catch (PDOException $e) {
        echo "❌ ত্রুটি হয়েছে: " . $e->getMessage();
    }
} else {
    echo "Invalid Request";
}
