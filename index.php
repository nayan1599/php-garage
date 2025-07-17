<?php
include './config/db.php';
include './config/session_check.php';
 

 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'layout/header.php';
include 'layout/sidebar.php';
?>
<div style="margin-left:220px; margin-top:56px; padding:1rem; margin-bottom: 56px;">


<?php
$page = $_GET['page'] ?? 'dashboard'; // ডিফল্ট পেজ

$page_path = $page . '.php';

if (file_exists($page_path)) {
    include $page_path;
} else {
    echo "<h3>Page not found: " . htmlspecialchars($page) . "</h3>";
}

echo '</div>';

include 'layout/footer.php';
?>
