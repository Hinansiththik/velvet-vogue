<?php
session_start();
include '../includes/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);

        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];

        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid admin email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Velvet Vogue</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="modern-auth-page">
    <div class="modern-auth-box">
        <div class="modern-auth-left admin-auth-left">
            <div class="modern-auth-brand">Velvet Vogue Admin</div>
            <h2>Welcome Back!</h2>
            <p>Login to manage products, orders, users, and customer inquiries.</p>
            <span class="modern-auth-outline-btn static-btn">ADMIN PANEL</span>
        </div>

        <div class="modern-auth-right">
            <h2>Admin Login</h2>

            <?php if ($message): ?>
                <div class="modern-auth-alert error-alert"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" class="modern-auth-form">
                <input type="email" name="email" placeholder="Admin Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="modern-auth-btn">SIGN IN</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>