<?php

// URL এর মাধ্যমে id আছে কিনা চেক করুন
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $gari_id = (int) $_GET['id'];

    // ডাটাবেজে রেকর্ড আছে কিনা চেক করুন
    $stmt = $pdo->prepare("SELECT * FROM gari WHERE gari_id = ?");
    $stmt->execute([$gari_id]);
    $gari = $stmt->fetch();

    if ($gari) {
        // রেকর্ড ডিলিট করুন
        $deleteStmt = $pdo->prepare("DELETE FROM gari WHERE gari_id = ?");
        if ($deleteStmt->execute([$gari_id])) {
            // সাকসেস মেসেজ সহ gari_list.php তে রিডাইরেক্ট
            header("Location: gari_list.php?msg=deleted");
            exit;
        } else {
            echo "<div class='alert alert-danger'>⚠️ গাড়ি ডিলিট করা যায়নি। আবার চেষ্টা করুন।</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>⚠️ কোনো গাড়ি পাওয়া যায়নি।</div>";
    }
} else {
    echo "<div class='alert alert-danger'>❗ ভুল বা অনুপস্থিত আইডি।</div>";
}
?>
