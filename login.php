<?php
session_start();
include './config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND status = 'Active'");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Update last login
        $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?")->execute([$user['user_id']]);

        header("Location: index.php");
        exit;
    } else {
        $error = "❌ ইউজারনেম বা পাসওয়ার্ড ভুল!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>লগইন</title>
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5 bg-light">

    <div class="row pt-5">
        <div class="col-md-5 offset-md-3 shadow-sm p-4 bg-white m-auto">
            <h1 class="text-center">🚗 জহিরুল সিস্টেমে স্বাগতম</h1>
            <p class="text-center">আপনার পরিচালনার জন্য লগইন করুন।</p>
            <h2 class="text-center">🔐 লগইন</h2>

            <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form method="post">
                <div class="mb-3">
                    <label>ইউজারনেম</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>পাসওয়ার্ড</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <!-- <div class="mb-3">
                    <label>ভূমিকা</label>
                    <select name="role" class="form-select">
                        <option value="Staff">স্টাফ</option>
                        <option value="Manager">ম্যানেজার</option>
                        <option value="Admin">অ্যাডমিন</option>
                    </select>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">মনে রাখুন</label>
                </div>
                <div class="mb-3">
                    <a href="forgot_password.php">পাসওয়ার্ড ভুলে গেছেন?</a>
                </div>
                <div class="mb-3">
                    <a href="register_user.php">নতুন ইউজার রেজিস্টার করুন</a>
                </div>
              
             
                <div class="mb-3">
                    <a href="logout.php">লগআউট</a>
                </div> -->
                <div class="text-end">

                    <button class="btn btn-success">লগইন করুন</button>
                </div>
            </form>
        </div>

</body>

</html>