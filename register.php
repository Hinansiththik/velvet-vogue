<?php
session_start();
include 'includes/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        $message = "Email already exists.";
    } else {
        $query = "INSERT INTO users (full_name, email, password, phone, address)
                  VALUES ('$full_name', '$email', '$password', '$phone', '$address')";

        if (mysqli_query($conn, $query)) {
            $message = "Registration successful. You can now login.";
        } else {
            $message = "Registration failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="modern-auth-page">
    <div class="modern-auth-box">
        <div class="modern-auth-left">
            <div class="modern-auth-brand">Velvet Vogue</div>
            <h2>Create Account</h2>
            <p>Join Velvet Vogue and enjoy a smooth fashion shopping experience.</p>
            <a href="login.php" class="modern-auth-outline-btn">SIGN IN</a>
        </div>

        <div class="modern-auth-right">
            <h2>Create Account</h2>

            <?php if ($message): ?>
                <div class="modern-auth-alert success-alert"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" class="modern-auth-form">
                <input type="text" name="full_name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <textarea name="address" placeholder="Address" required></textarea>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="modern-auth-btn">SIGN UP</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>