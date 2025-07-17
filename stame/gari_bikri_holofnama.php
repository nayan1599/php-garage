<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // সাধারণ তথ্য
    $seller_name = htmlspecialchars($_POST['seller_name']);
    $seller_nid = htmlspecialchars($_POST['seller_nid']);
    $seller_tin = htmlspecialchars($_POST['seller_tin']);
    $seller_father = htmlspecialchars($_POST['seller_father']);
    $seller_mother = htmlspecialchars($_POST['seller_mother']);
    $seller_address = htmlspecialchars($_POST['seller_address']);

    $buyer_name = htmlspecialchars($_POST['buyer_name']);
    $buyer_father = htmlspecialchars($_POST['buyer_father']);
    $buyer_address = htmlspecialchars($_POST['buyer_address']);
    $price = htmlspecialchars($_POST['price']);

    // গাড়ির তথ্য
    $vehicle_type = htmlspecialchars($_POST['vehicle_type']);
    $reg_no = htmlspecialchars($_POST['reg_no']);
    $engine_no = htmlspecialchars($_POST['engine_no']);
    $chassis_no = htmlspecialchars($_POST['chassis_no']);
    $manufacturer = htmlspecialchars($_POST['manufacturer']);
    $manuf_year = htmlspecialchars($_POST['manuf_year']);
    $horsepower = htmlspecialchars($_POST['horsepower']);

    $date = htmlspecialchars($_POST['date']);
?>
    <!DOCTYPE html>
    <html lang="bn">

    <head>
        <meta charset="UTF-8">
        <title>ফরম-২০ ও ২১</title>
        <style>
            body {
                font-family: 'SolaimanLipi', sans-serif;
                background: #f4f4f4;
                padding: 20px;
            }

            .container {
                background: #fff;
                padding: 30px;
                max-width: 800px;
                /* margin: auto; */
                /* border: 1px solid #ccc; */
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

                .sidebar {
                    display: none;
                }

                body {
                    background: #fff;
                }
            }

            ul {
                list-style: none;
                padding: 0;
            }

            ul li {
                padding: 5px 0;
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
            <h2>ফরম-২০<br>মোটরযানের মালিকানা স্বত্ব পরিবর্তন সংক্রান্ত বিক্রয়োত্তর ঘোষণাপত্র</h2>
            <p>রেজিস্ট্রেশন কর্তৃপক্ষ: ...................................................................................................</p>
            <p>আমি/আমরা: <?= $seller_name ?><br>
                জাতীয় পরিচয়পত্র নম্বর: <?= $seller_nid ?> &nbsp;&nbsp; টিআইএন নম্বরঃ <?= $seller_tin ?><br>
                পিতা/স্বামী: <?= $seller_father ?><br>
                মাতা: <?= $seller_mother ?><br>
                ঠিকানা: <?= $seller_address ?></p>

            <p>এতদ্বারা জানাইতেছি যে, মোটরযান যাহার: <?= $vehicle_type ?> ধরন: <?= $vehicle_type ?><br>
                রেজিস্ট্রেশন নম্বর: <?= $reg_no ?> ইঞ্জিন নম্বরঃ <?= $engine_no ?><br>
                চেসিস নম্বর: <?= $chassis_no ?><br>
                হর্সপাওয়ার: <?= $horsepower ?><br>
                প্রস্তুতকারক: <?= $manufacturer ?> প্রস্তুতকাল: <?= $manuf_year ?><br>
                নিম্নবর্ণিত ব্যক্তি/প্রতিষ্ঠানের নিকট বিক্রয় করিয়াছি:<br>
                জনাব: <?= $buyer_name ?><br>
                পিতা/স্বামী: <?= $buyer_father ?><br>
                ঠিকানা: <?= $buyer_address ?></p>

            <p>মোটরযানটি জনাব: <?= $buyer_name ?> এর অনুকূলে মালিকানা বদলি করিবার জন্য অনুরোধ জানাইতেছি।</p>

            <p>তারিখ: <?= $date ?></p>
            <p>স্বাক্ষরকারীর (বিক্রেতা) স্বাক্ষর:<br>
                ________________________________</p>

            <hr>

            <h2>ফরম-২১<br>বিক্রয় রশিদ</h2>
            <p>আমি/আমরা: <?= $seller_name ?><br>
                জাতীয় পরিচয়পত্র নম্বর: <?= $seller_nid ?> &nbsp;&nbsp; টিআইএন নম্বরঃ <?= $seller_tin ?><br>
                পিতা/স্বামী: <?= $seller_father ?><br>
                মাতা: <?= $seller_mother ?><br>
                ঠিকানা: <?= $seller_address ?></p>

            <p>এতদ্বারা জানাইতেছি যে, আমার/আমাদের মোটরযান যাহার: <?= $vehicle_type ?> ধরন: <?= $vehicle_type ?><br>
                রেজিস্ট্রেশন নম্বর: <?= $reg_no ?> ইঞ্জিন নম্বরঃ <?= $engine_no ?><br>
                চেসিস নম্বর: <?= $chassis_no ?><br>
                প্রস্তুতকারক: <?= $manufacturer ?> প্রস্তুতকাল: <?= $manuf_year ?><br>
                জনাব: <?= $buyer_name ?><br>
                পিতা/স্বামী: <?= $buyer_father ?><br>
                ঠিকানা: <?= $buyer_address ?><br>
                এর নিকট <?= $price ?> টাকা মূল্যে বিক্রয় করিলাম এবং নিম্নবর্ণিত স্বাক্ষীগণের সম্মুখে সমুদয় টাকা বুঝিয়া পাইলাম।</p>

            <p>তারিখ: <?= $date ?></p>

            <p>স্বাক্ষীর স্বাক্ষর, নাম, ঠিকানা ও মোবাইল নম্বর</p>
            <p>১। ............................................................</p>
            <p>২। ............................................................</p>
            <p>৩। ............................................................</p>
            <p>রেভিনিউ স্ট্যাম্প</p>

            <button class="print-btn" onclick="window.print()">🖨️ প্রিন্ট করুন</button>
            <a href="gari_bikri_holofnama.php" class="print-btn" style="background:#6c757d;">↩️ নতুন এন্ট্রি</a>
        </div>
        </div>
    </body>

    </html>
<?php
} else {
?>
    <!DOCTYPE html>
    <html lang="bn">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

    <head>
        <meta charset="UTF-8">
        <title>মোটরযান বিক্রয় ফর্ম</title>
    </head>

    <body>
        <div class="container">
            <h2>মোটরযান বিক্রয় ফর্ম</h2>
            <form method="POST">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h4>বিক্রেতার তথ্য</h4>
                        নাম: <input type="text" name="seller_name" class="form-control" required>
                        জাতীয় পরিচয়পত্র নম্বর: <input type="text" name="seller_nid" class="form-control" required>
                        টিআইএন নম্বরঃ <input type="text" name="seller_tin" class="form-control">
                        পিতা/স্বামী: <input type="text" name="seller_father" class="form-control" required>
                        মাতা: <input type="text" name="seller_mother" class="form-control">
                        ঠিকানা: <input type="text" name="seller_address" class="form-control" required>

                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h4>ক্রেতার তথ্য</h4>
                        নাম: <input type="text" name="buyer_name" class="form-control" required>
                        পিতা/স্বামী: <input type="text" name="buyer_father" class="form-control" required>
                        ঠিকানা: <input type="text" name="buyer_address" class="form-control" required>

                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h4>গাড়ির তথ্য</h4>
                        ধরন: <input type="text" name="vehicle_type" class="form-control" required>
                        রেজিস্ট্রেশন নম্বর: <input type="text" name="reg_no" class="form-control" required>
                        ইঞ্জিন নম্বর: <input type="text" name="engine_no" class="form-control" required>
                        চেসিস নম্বর: <input type="text" name="chassis_no" class="form-control" required>
                        হর্সপাওয়ার: <input type="text" name="horsepower" class="form-control">
                        প্রস্তুতকারক: <input type="text" name="manufacturer" class="form-control">
                        প্রস্তুতকাল: <input type="text" name="manuf_year" class="form-control">
                        বিক্রয় মূল্য (টাকা): <input type="text" name="price" class="form-control" required>
                        তারিখ: <input type="date" name="date" class="form-control" required>

                    </div>
                </div>










                <button type="submit" class="btn btn-success" value="প্রিন্ট আউট তৈরি করুন">প্রিন্ট আউট তৈরি করুন</button>
            </form>
        </div>
    </body>

    </html>
<?php
}
?>