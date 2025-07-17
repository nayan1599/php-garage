<?php
include './config/db.php';

header("Content-Type: text/html; charset=UTF-8");
$is_submitted = isset($_POST['submit']);

if ($is_submitted) {
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO bikrinama (
            seller_org, seller_name, seller_father, seller_mother, seller_address,
            seller_nationality, seller_religion, seller_profession, seller_nid,
            buyer_name, buyer_father, buyer_address, buyer_nationality, buyer_religion, buyer_profession,
            reg_no, chassis_no, engine_no, made_year, cc, seat_no, color, gari_price,
            witness_1, witness_2, witness_3,created_date
        ) VALUES (
            :seller_org, :seller_name, :seller_father, :seller_mother, :seller_address,
            :seller_nationality, :seller_religion, :seller_profession, :seller_nid,
            :buyer_name, :buyer_father, :buyer_address, :buyer_nationality, :buyer_religion, :buyer_profession,
            :reg_no, :chassis_no, :engine_no, :made_year, :cc, :seat_no, :color, :gari_price,
            :witness_1, :witness_2, :witness_3, :created_date
        )");

        $stmt->execute([
            ':seller_org' => $_POST['seller_org'],
            ':seller_name' => $_POST['seller_name'],
            ':seller_father' => $_POST['seller_father'],
            ':seller_mother' => $_POST['seller_mother'],
            ':seller_address' => $_POST['seller_address'],
            ':seller_nationality' => $_POST['seller_nationality'],
            ':seller_religion' => $_POST['seller_religion'],
            ':seller_profession' => $_POST['seller_profession'],
            ':seller_nid' => $_POST['seller_nid'],
            ':buyer_name' => $_POST['buyer_name'],
            ':buyer_father' => $_POST['buyer_father'],
            ':buyer_address' => $_POST['buyer_address'],
            ':buyer_nationality' => $_POST['buyer_nationality'],
            ':buyer_religion' => $_POST['buyer_religion'],
            ':buyer_profession' => $_POST['buyer_profession'],
            ':reg_no' => $_POST['reg_no'],
            ':chassis_no' => $_POST['chassis_no'],
            ':engine_no' => $_POST['engine_no'],
            ':made_year' => $_POST['made_year'],
            ':cc' => $_POST['cc'],
            ':seat_no' => $_POST['seat_no'],
            ':color' => $_POST['color'],
            ':gari_price' => $_POST['gari_price'],
            ':witness_1' => $_POST['witness_1'],
            ':witness_2' => $_POST['witness_2'],
            ':witness_3' => $_POST['witness_3'],
            ':created_date' => $_POST['created_date']
        ]);

        $success = true;
    } catch (PDOException $e) {
        die("❌ ডাটাবেজ সংরক্ষণ ব্যর্থ: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8" />
    <title>পুরাতন গাড়ি বিক্রয় দলিল</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: "Siyam Rupali", "SolaimanLipi", Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            background: white;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2,
        h3 {
            text-decoration: underline;
            margin-bottom: 25px;
            text-align: center;
        }

        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature-block {
            width: 45%;
            text-align: center;
        }

        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }

            #printable,
            #printable * {
                visibility: visible;
            }

            #printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
            }

            #print-button {
                display: none !important;
            }
        }

            

    </style>
</head>

<body>

    <div class="container">
        
            <h2>পুরাতন গাড়ি বিক্রয় দলিল ফর্ম</h2>
            <form method="post" novalidate action="">
                <h3>১ম পক্ষ (বিক্রেতা)</h3>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">বিক্রেতা প্রতিষ্ঠান</label>
                        <input type="text" class="form-control" name="seller_org" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">বিক্রেতা নাম</label>
                        <input type="text" class="form-control" name="seller_name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">পিতা</label>
                        <input type="text" class="form-control" name="seller_father" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">মাতা</label>
                        <input type="text" class="form-control" name="seller_mother" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ঠিকানা</label>
                        <textarea class="form-control" name="seller_address" rows="2" required></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">জাতীয়তা</label>
                        <input type="text" class="form-control" name="seller_nationality" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ধর্ম</label>
                        <input type="text" class="form-control" name="seller_religion"  required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">পেশা</label>
                        <input type="text" class="form-control" name="seller_profession" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ভোটার আইডি নং</label>
                        <input type="text" class="form-control" name="seller_nid" required>
                    </div>
                </div>

                <h3>২য় পক্ষ (ক্রেতা)</h3>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">ক্রেতা নাম</label>
                        <input type="text" class="form-control" name="buyer_name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">পিতা</label>
                        <input type="text" class="form-control" name="buyer_father" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ঠিকানা</label>
                        <textarea class="form-control" name="buyer_address" rows="2" required></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">জাতীয়তা</label>
                        <input type="text" class="form-control" name="buyer_nationality"required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ধর্ম</label>
                        <input type="text" class="form-control" name="buyer_religion"  required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">পেশা</label>
                        <input type="text" class="form-control" name="buyer_profession" required>
                    </div>
                </div>

                <h3>গাড়ির তথ্য</h3>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">রেজি নং</label>
                        <input type="text" class="form-control" name="reg_no"  required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">চেসিস নং</label>
                        <input type="text" class="form-control" name="chassis_no" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ইঞ্জিন নং</label>
                        <input type="text" class="form-control" name="engine_no" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">তৈরির সন</label>
                        <input type="text" class="form-control" name="made_year" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">সিসি</label>
                        <input type="text" class="form-control" name="cc" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">আসন সংখ্যা</label>
                        <input type="text" class="form-control" name="seat_no" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">রং</label>
                        <input type="text" class="form-control" name="color" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">মূল্য (টাকা)</label>
                        <input type="text" class="form-control" name="gari_price" required>
                    </div>
                </div>

                <h3>স্বাক্ষীগণ (ঐচ্ছিক)</h3>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">১. মো:</label>
                        <input type="text" class="form-control" name="witness_1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">২. মো:</label>
                        <input type="text" class="form-control" name="witness_2">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">৩.</label>
                        <input type="text" class="form-control" name="witness_3" placeholder="">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">তারিখ</label>
                        <input type="date" class="form-control" name="created_date" required>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-primary btn-lg">দলিল তৈরি করুন</button>
                </div>
            </form>

        
    <!-- Bootstrap 5 JS Bundle (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>