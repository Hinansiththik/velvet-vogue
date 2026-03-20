<?php
session_start();
include 'includes/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];

        header("Location: index.php");
        exit;
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="modern-auth-page">
    <div class="modern-auth-box">
        <div class="modern-auth-left">
            <div class="modern-auth-brand">Velvet Vogue</div>
            <h2>Welcome Back!</h2>
            <p>To keep connected with us please login with your personal information.</p>
            <a href="register.php" class="modern-auth-outline-btn">SIGN UP</a>
        </div>

        <div class="modern-auth-right">
            <h2>Customer Login</h2>

            <?php if ($message): ?>
                <div class="modern-auth-alert error-alert"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" class="modern-auth-form">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="modern-auth-btn">SIGN IN</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>