<?php
$current_page = $_GET['page'] ?? '';
?>

<style>
  .sidebar {
    height: 100vh;
    width: 220px;
    position: fixed;
    top: 56px;
    left: 0;
    background: rgb(5, 5, 5);
    padding-top: 1rem;
    overflow-y: auto;
  }

  .sidebar .nav-link {
    color: red;
    font-weight: bold;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }

  li ul li a {
    color: #fff !important;
  }

  .sidebar .nav-link.active {
    background-color: #0d6efd;
    color: #fff !important;
  }
</style>

<div class="sidebar">
  <ul class="nav flex-column">
    <!-- dashboard -->


    <li class="nav-item"><a class="nav-link <?= ($current_page == 'dashboard') ? 'active' : '' ?>" href="index.php?page=dashboard">🏠 ড্যাশবোর্ড</a></li>


    <!-- কাস্টমার মডিউল -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#customerMenu" role="button"
        aria-expanded="<?= (strpos($current_page, 'customer/') === 0) ? 'true' : 'false' ?>"
        aria-controls="customerMenu">
        👥 কাস্টমার মডিউল
      </a>
      <div class="collapse <?= (strpos($current_page, 'customer/') === 0) ? 'show' : '' ?>" id="customerMenu">
        <ul class="nav flex-column ms-3">
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'customer/dashboard') ? 'active' : '' ?>" href="index.php?page=customer/dashboard">🏠 কাস্টমার ড্যাশবোর্ড</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'customer/add') ? 'active' : '' ?>" href="index.php?page=customer/add">নতুন কাস্টমার</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'customer/index') ? 'active' : '' ?>" href="index.php?page=customer/index">কাস্টমার লিস্ট</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'customer/report/customer_report') ? 'active' : '' ?>" href="index.php?page=customer/report/customer_report">কাস্টমার রিপোর্ট</a></li>
        </ul>
      </div>
    </li>
    <!-- গাড়ি মডিউল -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#gariMenu" role="button"
        aria-expanded="<?= (strpos($current_page, 'gari/') === 0 && $current_page != 'gari/dashboard') ? 'true' : 'false' ?>"
        aria-controls="gariMenu">
        🛺 গাড়ি মডিউল
      </a>
      <div class="collapse <?= (strpos($current_page, 'gari/') === 0) ? 'show' : '' ?>" id="gariMenu">
        <ul class="nav flex-column ms-3">
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'gari/dashboard') ? 'active' : '' ?>" href="index.php?page=gari/dashboard">🏠 গাড়ি ড্যাশবোর্ড</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'gari/index') ? 'active' : '' ?>" href="index.php?page=gari/index">গাড়ি লিস্ট</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'gari/add') ? 'active' : '' ?>" href="index.php?page=gari/add">নতুন গাড়ি</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'gari/report/gari_report') ? 'active' : '' ?>" href="index.php?page=gari/report/gari_report">গাড়ির রিপোর্ট</a></li>

        </ul>
      </div>
    </li>
    <!-- ভাড়া দেওয়া মডিউল -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#rentedMenu" role="button"
        aria-expanded="<?= (strpos($current_page, 'rented/') === 0 && $current_page != 'rented/dashboard') ? 'true' : 'false' ?>"
        aria-controls="rentedMenu">
        🛠️ ভাড়া দেওয়া মডিউল
      </a>
      <div class="collapse <?= (strpos($current_page, 'rented/') === 0) ? 'show' : '' ?>" id="rentedMenu">
        <ul class="nav flex-column ms-3">
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'rented/dashboard') ? 'active' : '' ?>" href="index.php?page=rented/dashboard">🏠 ভাড়া ড্যাশবোর্ড</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'rented/index') ? 'active' : '' ?>" href="index.php?page=rented/index">ভাড়া দেওয়া গাড়ি লিস্ট</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'rented/add') ? 'active' : '' ?>" href="index.php?page=rented/add">নতুন ভাড়া দেওয়া</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'rented/report/rented_report') ? 'active' : '' ?>" href="index.php?page=rented/report/rented_report">ভাড়া রিপোর্ট</a></li>
        </ul>
    </li>
    <!-- গাড়ি ভাড়া মডিউল -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#daily_paymentMenu" role="button"
        aria-expanded="<?= (strpos($current_page, 'daily_payment/') === 0 && $current_page != 'daily_payment/dashboard') ? 'true' : 'false' ?>"
        aria-controls="daily_paymentMenu">
        💵 গাড়ি ভাড়া মডিউল
      </a>
      <div class="collapse <?= (strpos($current_page, 'daily_payment/') === 0) ? 'show' : '' ?>" id="daily_paymentMenu">
        <ul class="nav flex-column ms-3">

          <li class="nav-item"><a class="nav-link <?= ($current_page == 'daily_payment/dashboard') ? 'active' : '' ?>" href="index.php?page=daily_payment/dashboard">🏠 ভাড়া ড্যাশবোর্ড</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'daily_payment/add') ? 'active' : '' ?>" href="index.php?page=daily_payment/add">নতুন ভাড়া</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'daily_payment/index') ? 'active' : '' ?>" href="index.php?page=daily_payment/index">ভাড়া পেমেন্ট লিস্ট</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'daily_payment/report/rental_payment_report') ? 'active' : '' ?>" href="index.php?page=daily_payment/report/rental_payment_report">ভাড়া পেমেন্ট রিপোর্ট</a></li>


        </ul>

    </li>

    <!-- কিস্তিতে বিক্রি -->

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#kisti_formMenu" role="button"
        aria-expanded="<?= (strpos($current_page, 'kisti_form/') === 0 && $current_page != 'kisti_form/dashboard') ? 'true' : 'false' ?>"
        aria-controls="kisti_formMenu">
        🛠️ কিস্তিতে বিক্রি
      </a>
      <div class="collapse <?= (strpos($current_page, 'kisti_form/') === 0) ? 'show' : '' ?>" id="kisti_formMenu">
        <ul class="nav flex-column ms-3">
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'kisti_form/dashboard') ? 'active' : '' ?>" href="index.php?page=kisti_form/dashboard">🏠 কিস্তি ড্যাশবোর্ড</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'kisti_form/index') ? 'active' : '' ?>" href="index.php?page=kisti_form/index">কিস্তি লিস্ট</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'kisti_form/add') ? 'active' : '' ?>" href="index.php?page=kisti_form/add">নতুন কিস্তি</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'kisti_form/report/installment_report') ? 'active' : '' ?>" href="index.php?page=kisti_form/report/installment_report">কিস্তি রিপোর্ট</a></li>
        </ul>
    </li>
    <!-- কিস্তি পেমেন্ট লিস্ট -->

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#kisti_paymentMenu" role="button"
        aria-expanded="<?= (strpos($current_page, 'kisti_payment/') === 0 && $current_page != 'kisti_payment/dashboard') ? 'true' : 'false' ?>"
        aria-controls="kisti_paymentMenu">
        💵 কিস্তি পেমেন্ট লিস্ট
      </a>
      <div class="collapse <?= (strpos($current_page, 'kisti_payment/') === 0) ? 'show' : '' ?>" id="kisti_paymentMenu">
        <ul class="nav flex-column ms-3">
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'kisti_payment/dashboard') ? 'active' : '' ?>" href="index.php?page=kisti_payment/dashboard">🏠 কিস্তি পেমেন্ট ড্যাশবোর্ড</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'kisti_payment/index') ? 'active' : '' ?>" href="index.php?page=kisti_payment/index">কিস্তি পেমেন্ট লিস্ট</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'kisti_payment/add') ? 'active' : '' ?>" href="index.php?page=kisti_payment/add">নতুন কিস্তি পেমেন্ট</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'kisti_payment/report/kisti_form_report') ? 'active' : '' ?>" href="index.php?page=kisti_payment/report/kisti_form_report">কিস্তি পেমেন্ট রিপোর্ট</a></li>
        </ul>
    </li>

    <!-- হিসাব মডিউল -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#accountingMenu" role="button"
        aria-expanded="<?= (strpos($current_page, 'accounting/') === 0 && $current_page != 'accounting/dashboard') ? 'true' : 'false' ?>"
        aria-controls="accountingMenu">
        📒 হিসাব মডিউল
      </a>
      <div class="collapse <?= (strpos($current_page, 'accounting/') === 0) ? 'show' : '' ?>" id="accountingMenu">
        <ul class="nav flex-column ms-3">
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'accounting/dashboard') ? 'active' : '' ?>" href="index.php?page=accounting/dashboard">🏠 হিসাব ড্যাশবোর্ড</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'accounting/index') ? 'active' : '' ?>" href="index.php?page=accounting/index">হিসাব লিস্ট</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'accounting/add_accounting') ? 'active' : '' ?>" href="index.php?page=accounting/add_accounting">নতুন হিসাব</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'accounting/report/report_all') ? 'active' : '' ?>" href="index.php?page=accounting/report/report_all">হিসাব রিপোর্ট</a></li>
        </ul>
      </div>
    </li>

    <!-- বিক্রয় রশিদ -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#stameMenu" role="button"
        aria-expanded="<?= (strpos($current_page, 'stame/') === 0 && $current_page != 'stame/dashboard') ? 'true' : 'false' ?>"
        aria-controls="stameMenu">
        দলিল তৈরি
      </a>
      <div class="collapse <?= (strpos($current_page, 'stame/') === 0) ? 'show' : '' ?>" id="stameMenu">
        <ul class="nav flex-column ms-3">
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'stame/gari_bikri_holofnama') ? 'active' : '' ?>" href="index.php?page=stame/gari_bikri_holofnama">বিক্রয় রশিদ</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'stame/motor_bikri_dolil') ? 'active' : '' ?>" href="index.php?page=stame/motor_bikri_dolil">গাড়ি বিক্রয় দলিল</a></li>
        </ul>
      </div>
    </li>




    <!-- বিক্রয় রশিদ -->
 


    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#varaMenu" role="button"
        aria-expanded="<?= (strpos($current_page, 'vara/') === 0 && $current_page != 'vara/dashboard') ? 'true' : 'false' ?>"
        aria-controls="varaMenu">
       💵💵 ভাড়া এন্ট্রির

      </a>
      <div class="collapse <?= (strpos($current_page, 'vara/') === 0) ? 'show' : '' ?>" id="varaMenu">
        <ul class="nav flex-column ms-3">
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'vara/dashboard') ? 'active' : '' ?>" href="index.php?page=vara/dashboard">ভাড়া ড্যাশবোর্ড</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'vara/add') ? 'active' : '' ?>" href="index.php?page=vara/add">ভাড়া যোগ ফর্ম</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'vara/index') ? 'active' : '' ?>" href="index.php?page=vara/index">ভাড়া লিস্ট</a></li>
          <li class="nav-item"><a class="nav-link <?= ($current_page == 'vara/report') ? 'active' : '' ?>" href="index.php?page=vara/report">ভাড়া রিপোর্ট</a></li>

        </ul>
      </div>
    </li>

<!-- vara 0 -->



  </ul>
</div>