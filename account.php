<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Velvet Vogue</title>
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
            <li><a href="account.php" class="active">Profile</a></li>
            <li><a href="contact.php">Contact Us</a></li>
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
                <h1>My Account</h1>
                <p>View your personal profile information</p>
            </div>

            <div class="top-actions">
                <input type="text" placeholder="Search Product" class="search-box">
                <a href="login.php" class="icon-btn">❤</a>
                <a href="cart.php" class="icon-btn">🛒</a>
                <a href="account.php" class="icon-btn">👤</a>
            </div>
        </div>

        <div class="account-modern-card">
            <div class="account-modern-header">
                <div class="account-avatar">👤</div>
                <div>
                    <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>

            <div class="account-modern-grid">
                <div class="account-info-box">
                    <h3>Full Name</h3>
                    <p><?php echo htmlspecialchars($user['full_name']); ?></p>
                </div>

                <div class="account-info-box">
                    <h3>Email</h3>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <div class="account-info-box">
                    <h3>Phone Number</h3>
                    <p><?php echo htmlspecialchars($user['phone']); ?></p>
                </div>

                <div class="account-info-box">
                    <h3>Address</h3>
                    <p><?php echo htmlspecialchars($user['address']); ?></p>
                </div>
            </div>

            <div class="account-actions">
                <a href="shop.php" class="btn">Continue Shopping</a>
                <a href="logout.php" class="btn secondary-btn">Logout</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>