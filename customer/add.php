 
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>👥 নতুন কাস্টমার যোগ করুন</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container ">
        <h2 class="mb-4 text-center">👥 নতুন কাস্টমার যোগ করুন</h2>
        <form action="index.php?page=customer/post_customer" method="post">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">প্রথম নাম</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">শেষ নাম</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">লিঙ্গ</label>
                    <select name="gender" class="form-select">
                        <option value="">নির্বাচন করুন</option>
                        <option value="Male">পুরুষ</option>
                        <option value="Female">মহিলা</option>
                        <option value="Other">অন্যান্য</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">জন্ম তারিখ</label>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">মোবাইল নাম্বার</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">ইমেইল</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">ঠিকানা</label>
                <textarea name="address" class="form-control"></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">শহর</label>
                    <select name="city" id="city" class="form-control" onchange="updateState()">
                        <option value="">শহর নির্বাচন করুন</option>
                        <option value="Dhaka">ঢাকা</option>
                        <option value="Chittagong">চট্টগ্রাম</option>
                        <option value="Khulna">খুলনা</option>
                        <option value="Rajshahi">রাজশাহী</option>
                        <option value="Barisal">বরিশাল</option>
                        <option value="Sylhet">সিলেট</option>
                        <option value="Rangpur">রংপুর</option>
                        <option value="Mymensingh">ময়মনসিংহ</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">জেলা</label>
                    <select name="state" id="state" class="form-control">
                        <option value="">জেলা দেখাবে</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">পোস্ট কোড</label>
                    <input type="text" name="postal_code" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">দেশ</label>
                    <input type="text" class="form-control" value="Bangladesh" disabled>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">জাতীয় পরিচয়পত্র</label>
                    <input type="text" name="national_id" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">পেশা</label>
                    <input type="text" name="occupation" class="form-control">
                </div>
                <div class="col-md-4">

                    <label class="form-label">কাস্টমার টাইপ</label>
                    <select name="customer_type" class="form-select">
                        <option value="Buyer">ক্রেতা</option>
                        <option value="Seller">বিক্রেতা</option>
                        <option value="Renter">ভাড়াটে</option>
                        <option value="Installment">কিস্তি</option>
                        <option value="All">সকল</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">নোটস</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">স্ট্যাটাস</label>
                <select name="status" class="form-select">
                    <option value="Active" selected>চালু</option>
                    <option value="Inactive">বন্ধ</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">✅ সংরক্ষণ করুন</button>
            <a href="index.php?page=customer/index" class="btn btn-secondary">↩️ ফিরে যান</a>
        </form>
    </div>


    <script>
        const cityStates = {
            "Dhaka": ["ঢাকা", "ফরিদপুর", "গাজীপুর", "গোপালগঞ্জ", "কিশোরগঞ্জ", "মাদারীপুর", "মানিকগঞ্জ", "মুন্সিগঞ্জ", "নারায়ণগঞ্জ", "নরসিংদী", "রাজবাড়ী", "শরীয়তপুর", "টাঙ্গাইল"],
            "Chittagong": ["চট্টগ্রাম", "বান্দরবান", "ব্রাহ্মণবাড়িয়া", "চাঁদপুর", "কুমিল্লা", "কক্সবাজার", "ফেনী", "খাগড়াছড়ি", "লক্ষ্মীপুর", "নোয়াখালী", "রাঙ্গামাটি"],
            "Khulna": ["খুলনা", "বাগেরহাট", "চুয়াডাঙ্গা", "যশোর", "ঝিনাইদহ", "কুষ্টিয়া", "মাগুরা", "মেহেরপুর", "নড়াইল", "সাতক্ষীরা"],
            "Rajshahi": ["রাজশাহী", "বগুড়া", "চাঁপাইনবাবগঞ্জ", "জয়পুরহাট", "নাটোর", "নওগাঁ", "পাবনা", "সিরাজগঞ্জ"],
            "Barisal": ["বরিশাল", "ভোলা", "ঝালকাঠি", "পটুয়াখালী", "পিরোজপুর", "বরগুনা"],
            "Sylhet": ["সিলেট", "হবিগঞ্জ", "মৌলভীবাজার", "সুনামগঞ্জ"],
            "Rangpur": ["রংপুর", "দিনাজপুর", "গাইবান্ধা", "কুড়িগ্রাম", "লালমনিরহাট", "নীলফামারী", "পঞ্চগড়", "ঠাকুরগাঁও"],
            "Mymensingh": ["ময়মনসিংহ", "জামালপুর", "নেত্রকোনা", "শেরপুর"]
        };

        function updateState() {
            let city = document.getElementById("city").value;
            let stateSelect = document.getElementById("state");
            stateSelect.innerHTML = '<option value="">জেলা দেখাবে</option>';

            if (cityStates[city]) {
                cityStates[city].forEach(function(state) {
                    let option = document.createElement("option");
                    option.value = state;
                    option.text = state;
                    stateSelect.appendChild(option);
                });
            }
        }
    </script>
</body>

</html>