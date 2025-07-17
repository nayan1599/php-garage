<?php
include './config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ সঠিক দলিল আইডি প্রদান করা হয়নি।");
}

$id = (int)$_GET['id'];

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM bikrinama WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    // print_r($row);

    if (!$row) {
        die("❌ দলিল পাওয়া যায়নি।");
    }
} catch (PDOException $e) {
    die("❌ ডাটাবেজ সংযোগে সমস্যা: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>বিক্রয় দলিল - আইডি <?= htmlspecialchars($id) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature-block {
            width: 45%;
            text-align: center;
        }

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

            #print-button,
            .back-button {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div id="printable">
            <h2 class="text-center text-decoration-underline mb-4">পুরাতন গাড়ি বিক্রয় দলিল</h2>



            <p><strong>১ম পক্ষ (বিক্রেতা):</strong> <strong><?= htmlspecialchars($row['seller_org']) ?> </strong></p>
            <p><?= htmlspecialchars($row['seller_name']) ?>,পিতা-<?= htmlspecialchars($row['seller_father']) ?>,মাতাঃ<?= htmlspecialchars($row['seller_mother']) ?>,
                <?= htmlspecialchars($row['seller_address']) ?>, জাতীয়তা: <?= htmlspecialchars($row['seller_nationality']) ?>, ধর্ম: <?= htmlspecialchars($row['seller_religion']) ?>,
                ভোটার আইডি নং- <?= htmlspecialchars($row['seller_nid']) ?>,
            </p>
            <p> ____________________(১ম পক্ষ)</p>



            <p><strong>২য় পক্ষ (ক্রেতা) :</strong></p>
            <p>
                <?= htmlspecialchars($row['buyer_name']) ?>, পিতা-<?= htmlspecialchars($row['buyer_father']) ?>, মাতাঃ<?= htmlspecialchars($row['buyer_mother']) ?>,
                <?= htmlspecialchars($row['buyer_address']) ?>, জাতীয়তা: <?= htmlspecialchars($row['buyer_nationality']) ?>, ধর্ম: <?= htmlspecialchars($row['buyer_religion']) ?>, পেশা: <?= htmlspecialchars($row['buyer_occupation']) ?>।
            </p>
             <p>____________________(২য় পক্ষ)</p>
            

<!-- 2 page  -->


 
<p>পরম করুনাময় আল্লাহ তায়ালার পবিত্র নাম স্মরণ করিয়া পুরাতন গাড়ী (সিএনজি) বিক্রয়ের দলিলের বয়ান আরম্ভ করিলাম। যেহেতু আমি ১ম পক্ষ বিক্রেতা অদ্য <?= date('d/m/Y', strtotime($row['created_date'])) ?> তারিখে গাড়ীখানা নিম্নোক্ত মূল্যে বিক্রয় করিলাম। গাড়ীর মূল্য <?= htmlspecialchars($row['gari_price']) ?> টাকা, ২য় পক্ষ মাদা তারিখ হইতে সম্পূর্ণ নির্ভেজালত হইয়া বুঝাইয়া দিলেন ২য় পক্ষের কোন প্রকার দাবি/দেনা থাকিবে না এবং প্রকাশ থাকে যে, উক্ত গাড়ীটি আমার (১ম পক্ষ) নামে থাকলে কোন মামলা মোকদ্দমা নাই। থাকিলে ১ম পক্ষ দায়ভার গ্রহণ করিতে বাধ্য থাকিবেন।</p>

<p><strong>গাড়ির বিবরণ:</strong> রেজি নং <?= htmlspecialchars($row['reg_no']) ?>, চেসিস নং <?= htmlspecialchars($row['chassis_no']) ?>, ইঞ্জিন নং <?= htmlspecialchars($row['engine_no']) ?>, তৈরির সন <?= htmlspecialchars($row['made_year']) ?>, সিসি <?= htmlspecialchars($row['cc']) ?>, আসন সংখ্যা <?= htmlspecialchars($row['seat_no']) ?>, রং <?= htmlspecialchars($row['color']) ?>।</p>

<p>আমি ১ম পক্ষ বিক্রেতা অদ্য <?= date('d/m/Y', strtotime($row['created_date'])) ?> তারিখে গাড়ীটি <?= htmlspecialchars($row['gari_price']) ?> টাকায় বিক্রয় করিলাম। ২য় পক্ষ সম্পূর্ণ টাকা বুঝাইয়া দিলেন, পরবর্তীতে কোনো দাবি থাকবে না। গাড়িটি যদি ১ম পক্ষের নামে থাকে, কোনো মামলা থাকিলে ১ম পক্ষ দায়ভার নেবেন।</p>

<p>উপরের উল্লেখিত ঘটনা উভয় পক্ষ পড়িয়া জানিয়া, বুঝিয়া, সুস্থ মস্তিষ্কে, অন্যের বিনা প্ররোচনায় স্বজ্ঞাগনের সম্মুখে নিজ নাম সহি সম্পাদন করিলাম। ইতি তারিখ : <?= date('d/m/Y', strtotime($row['created_date'])) ?> ইং</p>

<h5 class="mt-4">স্বাক্ষীগনের স্বাক্ষর:</h5>
 <p>(১)____________________</p>
 <p>(২)____________________</p>
 <p>(৩)____________________</p>

<div class="signature-section">
    <div class="signature-block">
        ___________________________<br>
        (১ম পক্ষ বিক্রেতা)<br>
        <strong><?= htmlspecialchars($row['seller_name']) ?></strong>
    </div>
    <div class="signature-block">
        ___________________________<br>
        (২য় পক্ষ ক্রেতা)<br>
        <strong><?= htmlspecialchars($row['buyer_name']) ?></strong>
    </div>
</div>

</html>