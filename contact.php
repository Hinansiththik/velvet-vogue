<?php
session_start();
include 'includes/db.php';

$message = "";
$name = "";
$email = "";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
    $user = mysqli_fetch_assoc($user_query);

    if ($user) {
        $name = $user['full_name'];
        $email = $user['email'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message_text = mysqli_real_escape_string($conn, $_POST['message']);

    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : "NULL";

    if ($name && $email && $message_text) {
        $query = "INSERT INTO inquiries (user_id, name, email, message)
                  VALUES ($user_id, '$name', '$email', '$message_text')";

        if (mysqli_query($conn, $query)) {
            $message = "Your inquiry has been sent successfully!";
        } else {
            $message = "Failed to send inquiry.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="brand">Velvet<br>Vogue</div>

        <ul class="sidebar-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Explore</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="my-orders.php">My Orders</a></li>
            <li><a href="account.php">Profile</a></li>
            <li><a href="contact.php" class="active">Contact Us</a></li>
        </ul>

        <div class="help-box">
            <h3>Need Help?</h3>
            <p>Discover the latest fashion trends and get support anytime.</p>
            <a href="contact.php" class="btn small-btn">Customer Service</a>
        </div>
    </aside>

    <main class="main-content full-width-content">
        <div class="topbar">
            <div>
                <h1>Customer Inquiry</h1>
                <p>Send us your message and we will get back to you</p>
            </div>

            <div class="top-actions">
                <input type="text" placeholder="Search Product" class="search-box">
                <a href="login.php" class="icon-btn">❤</a>
                <a href="cart.php" class="icon-btn">🛒</a>
                <a href="account.php" class="icon-btn">👤</a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="checkout-message-box"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="inquiry-layout-modern">
            <div class="inquiry-form-modern">
                <h2>Send Inquiry</h2>

                <form method="POST" action="">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Your Name" required>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Your Email" required>
                    <textarea name="message" placeholder="Write your message here..." required></textarea>
                    <button type="submit" class="btn">Send Inquiry</button>
                </form>
            </div>

            <div class="inquiry-info-modern">
                <h2>Contact Information</h2>
                <div class="inquiry-info-box">
                    <h3>Email Support</h3>
                    <p>support@velvetvogue.com</p>
                </div>
                <div class="inquiry-info-box">
                    <h3>Phone</h3>
                    <p>+94 77 123 4567</p>
                </div>
                <div class="inquiry-info-box">
                    <h3>Address</h3>
                    <p>Velvet Vogue, Colombo, Sri Lanka</p>
                </div>
                <div class="inquiry-info-box">
                    <h3>Business Hours</h3>
                    <p>Mon - Sat : 9.00 AM - 7.00 PM</p>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>