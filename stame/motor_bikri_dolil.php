<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // বিক্রেতা তথ্য
    $seller_name = htmlspecialchars($_POST['seller_name']);
    $seller_father = htmlspecialchars($_POST['seller_father']);
    $seller_mother = htmlspecialchars($_POST['seller_mother']);
    $seller_address = htmlspecialchars($_POST['seller_address']);
    $seller_nid = htmlspecialchars($_POST['seller_nid']);
    $seller_profession = htmlspecialchars($_POST['seller_profession']);

    // ক্রেতা তথ্য
    $buyer_name = htmlspecialchars($_POST['buyer_name']);
    $buyer_father = htmlspecialchars($_POST['buyer_father']);
    $buyer_address = htmlspecialchars($_POST['buyer_address']);
    $buyer_profession = htmlspecialchars($_POST['buyer_profession']);

    // গাড়ি তথ্য
    $vehicle_type = htmlspecialchars($_POST['vehicle_type']);
    $reg_no = htmlspecialchars($_POST['reg_no']);
    $engine_no = htmlspecialchars($_POST['engine_no']);
    $chassis_no = htmlspecialchars($_POST['chassis_no']);
    $manuf_year = htmlspecialchars($_POST['manuf_year']);
    $seat = htmlspecialchars($_POST['seat']);
    $color = htmlspecialchars($_POST['color']);
    $price = htmlspecialchars($_POST['price']);
    $date = htmlspecialchars($_POST['date']);
?>
    <!DOCTYPE html>
    <html lang="bn">

    <head>
        <meta charset="UTF-8">
        <title>গাড়ি বিক্রয় দলিল</title>
        <style>
            body {
                font-family: 'SolaimanLipi', sans-serif;
                background: #f4f4f4;
                padding: 20px;
            }

            .container {
                background: #fff;
                padding: 30px;
                max-width: 850px;
                margin: auto;
                border: 1px solid #ccc;
                border-radius: 10px;
            }

            h2 {
                text-align: center;
                text-decoration: underline;
            }

            .print-btn {
                display: block;
                width: 150px;
                margin: 20px auto;
                padding: 10px;
                background: #007BFF;
                color: #fff;
                text-align: center;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }

            @media print {
                .print-btn {
                    display: none;
                }

                body {
                    background: #fff;
                }
            }

            p {
                text-align: justify;
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
    </head>

    <body>
        <div class="print-area">
        <div class="container">
            <h2>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</h2>
            <p style="text-align:center;">৫০০ টাকা &nbsp;&nbsp; ৫০০ টাকা &nbsp;&nbsp; একশত টাকা</p>
            <p style="text-align:center;">নং: ..................................</p>
            <p style="text-align:center;">বিসমিল্লাহির রাহমানির রাহিম</p>
            <p>পরম করুনাময় আল্লাহ তায়ালার পবিত্র নাম স্মরণ করিয়া একটি পুরাতন গাড়ী (<?= $vehicle_type ?>) বিক্রয়ের দলিলের বয়ান আরম্ভ করিলাম। যেহেতু আমি ১ম পক্ষ <?= $seller_name ?>, পিতা: <?= $seller_father ?>, মাতা: <?= $seller_mother ?>, পেশা: <?= $seller_profession ?>, ঠিকানা: <?= $seller_address ?>, ভোটার আইডি নং <?= $seller_nid ?> উক্ত গাড়ীর মালিক বটে, আমার বিশেষ টাকার প্রয়োজনে অদ্যকার গাড়ীখানা বিক্রয়ের মত প্রকাশ করিলে আপনি ২য় পক্ষ <?= $buyer_name ?>, পিতা: <?= $buyer_father ?>, ঠিকানা: <?= $buyer_address ?>, পেশা: <?= $buyer_profession ?> বর্তমান বাজার দর অনুযায়ী গাড়ীখানা ক্রয় এর জন্য সম্মতি প্রকাশ করেন।</p>

            <p>যাহার রেজিঃ নং: <?= $reg_no ?> (যদি থাকে) চেসিস নং: <?= $chassis_no ?> ইঞ্জিন নং: <?= $engine_no ?> তৈরির সন: <?= $manuf_year ?> আসন সংখ্যা: <?= $seat ?> রং: <?= $color ?>। আমি ১ম পক্ষ বিক্রেতা অদ্য <?= $date ?> তারিখে গাড়ীখানা নিম্নোক্ত মূল্যে বিক্রয় করিলাম: গাড়ী মূল্য: <?= $price ?> টাকা। ১ম পক্ষ অদ্য তারিখ হইতে সম্পূর্ণ নিঃশর্তবান হইয়া বুঝাইয়া দিলেন, ২য় পক্ষের কোন প্রকার দায়/দেনা থাকিবে না। এর প্রকাশ থাকে যে, উক্ত গাড়িটি আমার (১ম পক্ষ) নামে থাকা কালে কোন মামলা মোকদ্দমা নাই। থাকিলে ১ম পক্ষ সকল দায়ভার গ্রহণ করিতে বাধ্য থাকিবেন।</p>

            <p>১। গাড়িটি মেয়াদ নবায়ন, কাগজপত্র নবায়ন, মামলা উত্তোলন ইত্যাদি সহ যে কোন দূর্ঘটনায় দায়দায়িত্ব ২য় পক্ষ বহন করিবে। ১ম পক্ষ কোন দায় বহন করিবে না।</p>
            <p>২। মেয়াদোত্তীর্ণ মালামাল বা অসামাজিক কার্যকলাপে রাষ্ট্রীয় গোয়েন্দা অফিসে ধরা পড়লে দায়দায়িত্ব ২য় পক্ষ বহন করিবে।</p>
            <p>৩। কিস্তি পরিশোধ, আংশিক কিস্তির টাকা পরিশোধে নির্ধারিত সময় ২য় পক্ষ পালন করিবে।</p>
            <p>৪। কিস্তি পরিশোধ শেষে ২য় পক্ষ নাম পরিবর্তন করিতে বাধ্য থাকিবে।</p>

            <p>উপরোক্ত চুক্তিমালা আমরা উভয় পক্ষ পড়িয়া, জানিয়া, বুঝিয়া, স্বেচ্ছায় স্বাক্ষর করিলাম।</p>
            <p>ইতি, তারিখ: <?= $date ?></p>

            <p>স্বাক্ষীগণের স্বাক্ষর:<br>
                ১। ........................................................<br>
                ২। ........................................................<br>
                ৩। ........................................................</p>

            <p>১ম পক্ষ (বিক্রেতা): ___________________________<br>
                ২য় পক্ষ (ক্রেতা): ___________________________<br>
                জামিনদার: ___________________________</p>

            <p style="text-align:center;">"দেশপ্রেমের শপথ নিন, দুর্নীতিকে বিদায় দিন"</p>

            <button class="print-btn" onclick="window.print()">🖨️ প্রিন্ট করুন</button>
            <a href="motor_bikri_dolil.php" class="print-btn" style="background:#6c757d;">↩️ নতুন এন্ট্রি</a>
        </div>
        </div>
    </body>

    </html>
<?php
} else {
?>
    <!DOCTYPE html>
    <html lang="bn">

    <head>
        <meta charset="UTF-8">
        <title>গাড়ি বিক্রয় দলিল ফর্ম</title>
        <style>
            body {
                font-family: 'SolaimanLipi', sans-serif;
                background: #f4f4f4;
                padding: 20px;
            }

            .container {
                background: #fff;
                padding: 30px;
                max-width: 600px;
                margin: auto;
                border: 1px solid #ccc;
                border-radius: 10px;
            }

            input[type="text"],
            input[type="date"],
            input[type="number"] {
                width: 100%;
                padding: 8px;
                margin: 5px 0 15px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            input[type="submit"] {
                padding: 10px 20px;
                background: #007BFF;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            h2 {
                text-align: center;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h2>গাড়ি বিক্রয় দলিল ফর্ম</h2>
            <form method="POST">
                <h4>১ম পক্ষ (বিক্রেতা) তথ্য</h4>
                নাম: <input type="text" name="seller_name" required>
                পিতা: <input type="text" name="seller_father" required>
                মাতা: <input type="text" name="seller_mother">
                পেশা: <input type="text" name="seller_profession">
                ঠিকানা: <input type="text" name="seller_address" required>
                ভোটার আইডি নং: <input type="text" name="seller_nid" required>

                <h4>২য় পক্ষ (ক্রেতা) তথ্য</h4>
                নাম: <input type="text" name="buyer_name" required>
                পিতা: <input type="text" name="buyer_father" required>
                ঠিকানা: <input type="text" name="buyer_address" required>
                পেশা: <input type="text" name="buyer_profession">

                <h4>গাড়ির তথ্য</h4>
                গাড়ির ধরন: <input type="text" name="vehicle_type" required>
                রেজিস্ট্রেশন নং: <input type="text" name="reg_no">
                চেসিস নং: <input type="text" name="chassis_no" required>
                ইঞ্জিন নং: <input type="text" name="engine_no" required>
                তৈরির সন: <input type="text" name="manuf_year">
                আসন সংখ্যা: <input type="text" name="seat">
                রং: <input type="text" name="color">

                বিক্রয় মূল্য (টাকা): <input type="number" name="price" required>
                তারিখ: <input type="date" name="date" required>

                <input type="submit" value="দলিল তৈরি করুন">
            </form>
        </div>
    </body>

    </html>
<?php
}
?>