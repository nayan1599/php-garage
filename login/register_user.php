<?php
include './config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, full_name, email, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password_hash, $full_name, $email, $role]);

    echo "✅ ইউজার সফলভাবে যোগ হয়েছে!";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>নতুন ইউজার রেজিস্টার</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2>➕ নতুন ইউজার রেজিস্টার</h2>
    <form method="post" class="mt-4">
        <div class="mb-2">
            <label>ইউজারনেম</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>পাসওয়ার্ড</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>পূর্ণ নাম</label>
            <input type="text" name="full_name" class="form-control">
        </div>
        <div class="mb-2">
            <label>ইমেইল</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-2">
            <label>ভূমিকা</label>
            <select name="role" class="form-control">
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
                <option value="Manager">Manager</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">✅ রেজিস্টার</button>
    </form>
</body>

</html>