<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='shop.php'>Go to Shop</a></p>";
    exit;
}

$total = 0;
$cart_items = [];

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_id = intval($product_id);
    $quantity = intval($quantity);

    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    if ($product) {
        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;

        $cart_items[] = [
            'product_id' => $product['product_id'],
            'product_name' => $product['product_name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $user_id = $_SESSION['user_id'];

    if ($full_name && $email && $phone && $address && $payment_method) {
        $order_query = "INSERT INTO orders (user_id, total_amount, payment_method, order_status)
                        VALUES ($user_id, '$total', '$payment_method', 'Pending')";

        if (mysqli_query($conn, $order_query)) {
            $order_id = mysqli_insert_id($conn);

            foreach ($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price)
                               VALUES ($order_id, $product_id, $quantity, '$price')";
                mysqli_query($conn, $item_query);
            }

         unset($_SESSION['cart']);
header("Location: receipt.php?order_id=" . $order_id);
exit;
        } else {
            $message = "Something went wrong. Please try again.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($user_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Velvet Vogue</title>
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
                <h1>Checkout</h1>
                <p>Complete your order securely</p>
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

        <?php if ($message !== "Order placed successfully!"): ?>
            <div class="checkout-layout-modern">
                <div class="checkout-form-modern">
                    <h2>Billing Details</h2>

                    <form method="POST" action="">
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" placeholder="Full Name" required>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email Address" required>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Phone Number" required>
                        <textarea name="address" placeholder="Delivery Address" required><?php echo htmlspecialchars($user['address']); ?></textarea>

                        <select name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="Cash on Delivery">Cash on Delivery</option>
                            <option value="Card on Delivery">Card on Delivery</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>

                        <button type="submit" class="btn">Place Order</button>
                    </form>
                </div>

                <div class="checkout-summary-modern">
                    <h2>Order Summary</h2>

                    <?php foreach ($cart_items as $item): ?>
                        <div class="checkout-summary-item">
                            <span><?php echo $item['product_name']; ?> x <?php echo $item['quantity']; ?></span>
                            <span>Rs. <?php echo number_format($item['subtotal'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="checkout-total-row">
                        <strong>Total</strong>
                        <strong>Rs. <?php echo number_format($total, 2); ?></strong>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-box">
                <h2>Order Completed</h2>
                <p>Your order has been placed successfully.</p>
                <a href="shop.php" class="btn">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>