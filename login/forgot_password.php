<?php
include './config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in DB (optional: create a password_resets table for this)
        $pdo->prepare("UPDATE users SET password_reset_token = ?, token_expire = ? WHERE user_id = ?")
            ->execute([$token, $expires, $user['user_id']]);

        // Normally email would be sent; we'll simulate link
        echo "🔗 পাসওয়ার্ড রিসেট লিঙ্ক: <a href='reset_password.php?token=$token'>এখানে ক্লিক করুন</a>";
    } else {
        echo "<div class='alert alert-danger'>এই ইমেইল আমাদের সিস্টেমে নেই।</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>পাসওয়ার্ড ভুলে গেছেন?</title>
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h3>🔐 পাসওয়ার্ড ভুলে গেছেন?</h3>
    <form method="post">
        <div class="mb-3">
            <label>আপনার ইমেইল দিন</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button class="btn btn-primary">📩 রিসেট লিঙ্ক পাঠান</button>
    </form>
</body>
</html>
