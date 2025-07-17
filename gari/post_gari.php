<?php
 
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ডাটাবেজ কানেকশন ব্যর্থ: " . $e->getMessage());
}

function cleanInt($val) {
    return (isset($val) && $val !== '') ? $val : null;
}

function cleanStr($val) {
    return (isset($val) && $val !== '') ? $val : null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // 🔍 ডুপ্লিকেট চেক
        $check_sql = "SELECT COUNT(*) FROM gari WHERE 
                        registration_number = :registration_number 
                        OR engine_number = :engine_number 
                        OR chassis_number = :chassis_number";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([
            ':registration_number' => $_POST['registration_number'],
            ':engine_number' => $_POST['engine_number'],
            ':chassis_number' => $_POST['chassis_number']
        ]);
        $exists = $check_stmt->fetchColumn();

        if ($exists > 0) {
            echo "<script>alert('⚠️ এই গাড়ির রেজিস্ট্রেশন, ইঞ্জিন বা চ্যাসিস নাম্বার ইতিমধ্যেই বিদ্যমান।'); window.location.href='index.php?page=gari/index';</script>";
            exit;
        }

        // ইনসার্ট
        $sql = "INSERT INTO gari (
                    gari_nam, brand, model, variant, color, baner_bochor, registration_number,
                    engine_number, chassis_number, fuel_type, transmission_type, mileage,
                    seat_sankhya, kinamulya, bikri_mulya, vara_dhor, vara_masik, kisti_mulya,
                    avastha, gari_dhoron, insurance_sesh, fitness_sesh, tax_sesh, bibran
                ) VALUES (
                    :gari_nam, :brand, :model, :variant, :color, :baner_bochor, :registration_number,
                    :engine_number, :chassis_number, :fuel_type, :transmission_type, :mileage,
                    :seat_sankhya, :kinamulya, :bikri_mulya, :vara_dhor, :vara_masik, :kisti_mulya,
                    :avastha, :gari_dhoron, :insurance_sesh, :fitness_sesh, :tax_sesh, :bibran
                )";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':gari_nam' => cleanStr($_POST['gari_nam']),
            ':brand' => cleanStr($_POST['brand']),
            ':model' => cleanStr($_POST['model']),
            ':variant' => cleanStr($_POST['variant']),
            ':color' => cleanStr($_POST['color']),
            ':baner_bochor' => cleanInt($_POST['baner_bochor']),
            ':registration_number' => cleanStr($_POST['registration_number']),
            ':engine_number' => cleanStr($_POST['engine_number']),
            ':chassis_number' => cleanStr($_POST['chassis_number']),
            ':fuel_type' => cleanStr($_POST['fuel_type']),
            ':transmission_type' => cleanStr($_POST['transmission_type']),
            ':mileage' => cleanInt($_POST['mileage']),
            ':seat_sankhya' => cleanInt($_POST['seat_sankhya']),
            ':kinamulya' => cleanInt($_POST['kinamulya']),
            ':bikri_mulya' => cleanInt($_POST['bikri_mulya']),
            ':vara_dhor' => cleanInt($_POST['vara_dhor']),
            ':vara_masik' => cleanInt($_POST['vara_masik']),
            ':kisti_mulya' => cleanInt($_POST['kisti_mulya']),
            ':avastha' => cleanStr($_POST['avastha']),
            ':gari_dhoron' => cleanStr($_POST['gari_dhoron']),
            ':insurance_sesh' => cleanStr($_POST['insurance_sesh']),
            ':fitness_sesh' => cleanStr($_POST['fitness_sesh']),
            ':tax_sesh' => cleanStr($_POST['tax_sesh']),
            ':bibran' => cleanStr($_POST['bibran'])
        ]);

        echo "<script>alert('✅ গাড়ির তথ্য সফলভাবে যোগ হয়েছে'); window.location.href='index.php?page=gari/index';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('❌ ত্রুটি হয়েছে: ". addslashes($e->getMessage()) ."'); window.location.href='index.php?page=gari/index';</script>";
    }
} else {
    echo "Invalid Request";
}
?>
