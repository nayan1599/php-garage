 <body>
     <div class="container ">
         <h2 class="text-center">🚗 নতুন গাড়ি যোগ করুন</h2>
         <form action="index.php?page=gari/post_gari" method="POST">
             <div class="row mb-3">
                 <div class="col-md-6">
                     <label>গাড়ির নাম</label>
                     <input type="text" name="gari_nam" class="form-control" required>
                 </div>
                 <div class="col-md-6">
                     <label for="brand">ব্র্যান্ড</label>
                     <select name="brand" class="form-select" required>
                         <option value="">-- ব্র্যান্ড নির্বাচন করুন --</option>
                         <option value="টয়োটা">টয়োটা</option>
                         <option value="নিসান">নিসান</option>
                         <option value="হোন্ডা">হোন্ডা</option>
                         <option value="মিতসুবিশি">মিতসুবিশি</option>
                         <option value="সুজুকি">সুজুকি</option>
                         <option value="হুন্ডাই">হুন্ডাই</option>
                         <option value="মাজদা">মাজদা</option>
                         <option value="কিয়া">কিয়া</option>
                         <option value="ফোর্ড">ফোর্ড</option>
                         <option value="বিএমডাব্লিউ">বিএমডাব্লিউ</option>
                     </select>

                 </div>
             </div>

             <div class="row mb-3">
                 <div class="col-md-4">
                     <label>মডেল</label>
                     <input type="text" name="model" class="form-control" required>
                 </div>
                 <div class="col-md-4">
                     <label for="variant">ভেরিয়েন্ট</label>
                     <select name="variant" class="form-select">
                         <option value="">-- ভেরিয়েন্ট নির্বাচন করুন --</option>
                         <option value="এক্সএল">এক্সএল</option>
                         <option value="এক্সজি">এক্সজি</option>
                         <option value="ভিএক্স">ভিএক্স</option>
                         <option value="ভিএক্সআই">ভিএক্সআই</option>
                         <option value="জেডএক্সআই">জেডএক্সআই</option>
                         <option value="জি এল">জি এল</option>
                         <option value="জি এল এক্স">জি এল এক্স</option>
                         <option value="জিএস">জিএস</option>
                         <option value="এসই">এসই</option>
                         <option value="লিমিটেড">লিমিটেড</option>
                     </select>

                 </div>
                 <div class="col-md-4">
                     <label>রঙ</label>
                     <input type="color" name="color" class="form-control">
                 </div>
             </div>

             <div class="row mb-3">
                 <div class="col-md-4">
                     <label>নির্মাণ সাল</label>
                     <input type="number" name="baner_bochor" class="form-control" min="1900" max="<?= date('Y') ?>" >
                 </div>
                 <div class="col-md-4">
                     <label>রেজিস্ট্রেশন নাম্বার</label>
                     <input type="text" name="registration_number" class="form-control" required>
                 </div>
                 <div class="col-md-4">
                     <label>ইঞ্জিন নাম্বার</label>
                     <input type="text" name="engine_number" class="form-control" required>
                 </div>
             </div>

             <div class="row mb-3">
                 <div class="col-md-4">
                     <label>চেসিস নাম্বার</label>
                     <input type="text" name="chassis_number" class="form-control" required>
                 </div>
                 <div class="col-md-4">
                     <label>জ্বালানি</label>
                     <select name="fuel_type" class="form-select">
                         <option value="Petrol">পেট্রোল</option>
                         <option value="Diesel">ডিজেল</option>
                         <option value="CNG">সিএনজি</option>
                         <option value="Electric">ইলেকট্রিক</option>
                         <option value="Hybrid">হাইব্রিড</option>

                     </select>
                 </div>
                 <div class="col-md-4">
                     <label>গিয়ার টাইপ</label>
                     <select name="transmission_type" class="form-select">
                         <option value="Manual">ম্যানুয়াল</option>
                         <option value="Automatic">অটোমেটিক</option>

                     </select>
                 </div>
             </div>

             <div class="row mb-3">
                 <div class="col-md-3"><label>মাইলেজ (KM)</label><input type="number" name="mileage" class="form-control"></div>
                 <div class="col-md-3"><label>আসনের সংখ্যা</label><input type="number" name="seat_sankhya" class="form-control"></div>
                 <div class="col-md-3"><label>ক্রয়মূল্য</label><input type="number" step="0.01" name="kinamulya" class="form-control"></div>
                 <div class="col-md-3"><label>বিক্রয়মূল্য</label><input type="number" step="0.01" name="bikri_mulya" class="form-control"></div>
             </div>

             <div class="row mb-3">
                 <div class="col-md-4"><label>দৈনিক ভাড়া</label><input type="number" step="0.01" name="vara_dhor" class="form-control"></div>
                 <div class="col-md-4"><label>মাসিক ভাড়া</label><input type="number" step="0.01" name="vara_masik" class="form-control"></div>
                 <div class="col-md-4"><label>কিস্তি মূল্য</label><input type="number" step="0.01" name="kisti_mulya" class="form-control"></div>
             </div>

             <div class="row mb-3">
                 <div class="col-md-4">
                     <label>অবস্থা</label>
                     <select name="avastha" class="form-select">
                         <option value="Available">উপলব্ধ</option>
                         <option value="Rented">ভাড়ায়</option>
                         <option value="Sold">বিক্রি হয়েছে</option>
                         <option value="Installment">কিস্তিতে</option>
                         <option value="Damaged">ক্ষতিগ্রস্ত</option>
                         <option value="Under Maintenance">রক্ষণাবেক্ষণে</option>
                         <option value="Other">অন্যান্য</option>
                     </select>
                 </div>
                 <div class="col-md-4">
                     <label>গাড়ির ধরন</label>
                     <select name="gari_dhoron" class="form-select">
                         <option value="CNG">সিএনজি /অটোরিকশা</option>
                         <option value="Car">গাড়ি /টেক্সি</option>
                         <option value="Bike">মোটরসাইকেল</option>
                         <option value="Truck">ট্রাক</option>
                         <option value="Van">ভ্যান</option>
                         <option value="SUV">এসইউভি</option>
                         <option value="Microbus">মাইক্রোবাস</option>
                         <option value="Bus">বাস</option>
                         <option value="Pickup">পিকআপ</option>
                         <option value="AutoRickshaw">অটোরিকশা</option>
                         <option value="Tempo">টেম্পু</option>
                         <option value="Tractor">ট্রাক্টর</option>
                         <option value="Jeep">জিপ</option>
                         <option value="Minibus">মিনিবাস</option>
                         <option value="ElectricBike">ইলেকট্রিক বাইক</option>
                         <option value="Easyrickshaw">ইজি বাইক</option>
                         <option value="RickshawVan">রিকশা ভ্যান</option>
                         <option value="Other">অন্যান্য</option>


                     </select>
                 </div>
                 <!-- <div class="col-md-4">
                    <label>ছবি (URL)</label>
                    <input type="text" name="image_url" class="form-control">
                </div> -->
             </div>

             <div class="row mb-3">
                 <div class="col-md-4"><label>ইন্স্যুরেন্স শেষ</label><input type="date" name="insurance_sesh" class="form-control"></div>
                 <div class="col-md-4"><label>ফিটনেস শেষ</label><input type="date" name="fitness_sesh" class="form-control"></div>
                 <div class="col-md-4"><label>ট্যাক্স শেষ</label><input type="date" name="tax_sesh" class="form-control"></div>
             </div>

             <div class="mb-3">
                 <label>বিবরণ</label>
                 <textarea name="bibran" class="form-control"></textarea>
             </div>

             <button type="submit" class="btn btn-success">🚗 গাড়ি সংরক্ষণ করুন</button>
             <a href="index.php?page=gari/index" class="btn btn-secondary">📄 তালিকা</a>
         </form>
     </div>
 </body>