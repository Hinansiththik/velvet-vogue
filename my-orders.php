<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

$orders_query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_id DESC";
$orders_result = mysqli_query($conn, $orders_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Velvet Vogue</title>
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
            <li><a href="account.php">Profile</a></li>
            <li><a href="my-orders.php" class="active">My Orders</a></li>
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
                <h1>My Orders</h1>
                <p>Track your purchases and view receipts</p>
            </div>

            <div class="top-actions">
                <input type="text" placeholder="Search Product" class="search-box">
                <a href="login.php" class="icon-btn">❤</a>
                <a href="cart.php" class="icon-btn">🛒</a>
                <a href="account.php" class="icon-btn">👤</a>
            </div>
        </div>

        <?php if (mysqli_num_rows($orders_result) > 0): ?>
            <div class="orders-modern-list">
                <?php while ($order = mysqli_fetch_assoc($orders_result)) : ?>
                    <div class="order-modern-card">
                        <div class="order-modern-top">
                            <div>
                                <h2>Order #<?php echo $order['order_id']; ?></h2>
                                <p><?php echo $order['created_at']; ?></p>
                            </div>
                            <span class="order-status-badge status-<?php echo strtolower($order['order_status']); ?>">
                                <?php echo $order['order_status']; ?>
                            </span>
                        </div>

                        <div class="order-modern-info">
                            <div class="order-info-box">
                                <h3>Total Amount</h3>
                                <p>Rs. <?php echo number_format($order['total_amount'], 2); ?></p>
                            </div>

                            <div class="order-info-box">
                                <h3>Payment Method</h3>
                                <p><?php echo htmlspecialchars($order['payment_method']); ?></p>
                            </div>

                            <div class="order-info-box">
                                <h3>Status</h3>
                                <p><?php echo htmlspecialchars($order['order_status']); ?></p>
                            </div>
                        </div>

                        <div class="order-modern-actions">
                            <a href="receipt.php?order_id=<?php echo $order['order_id']; ?>" class="btn">View Receipt</a>
                            <a href="shop.php" class="btn secondary-btn">Shop Again</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                <h2>No Orders Yet</h2>
                <p>You have not placed any orders yet.</p>
                <a href="shop.php" class="btn">Start Shopping</a>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>