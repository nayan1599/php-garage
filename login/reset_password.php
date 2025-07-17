<?php
include './config/db.php';
include './config/session_check.php';
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE password_reset_token = ? AND token_expire > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        die("⛔ অবৈধ বা মেয়াদোত্তীর্ণ টোকেন।");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_password = $_POST['new_password'];
        $hash = password_hash($new_password, PASSWORD_DEFAULT);

        $pdo->prepare("UPDATE users SET password_hash = ?, password_reset_token = NULL, token_expire = NULL WHERE user_id = ?")
            ->execute([$hash, $user['user_id']]);

        echo "✅ পাসওয়ার্ড সফলভাবে আপডেট হয়েছে। এখন <a href='login/login.php'>লগইন করুন</a>";
        exit;
    }
} else {
    die("⛔ টোকেন পাওয়া যায়নি।");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>নতুন পাসওয়ার্ড দিন</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h3>🔁 নতুন পাসওয়ার্ড দিন</h3>
    <form method="post">
        <div class="mb-3">
            <label>নতুন পাসওয়ার্ড</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <button class="btn btn-success">🔄 পাসওয়ার্ড রিসেট করুন</button>
    </form>
</body>
</html>
