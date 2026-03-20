<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

    if ($quantity < 1) $quantity = 1;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // ✅ NEW: handle Buy Now
    if (isset($_GET['buy_now'])) {
        header("Location: checkout.php");
        exit;
    }

    header("Location: cart.php");
    exit;
}

if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit;
}

if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        $quantity = intval($quantity);

        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }

    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="brand">Velvet<br>Vogue</div>

        <ul class="sidebar-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Explore</a></li>
            <li><a href="cart.php" class="active">Cart</a></li>
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
                <h1>Shopping Cart</h1>
                <p>Review your selected products</p>
            </div>

            <div class="top-actions">
                <input type="text" placeholder="Search Product" class="search-box">
                <a href="login.php" class="icon-btn">❤</a>
                <a href="cart.php" class="icon-btn">🛒</a>
                <a href="account.php" class="icon-btn">👤</a>
            </div>
        </div>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="empty-box">
                <h2>Your cart is empty</h2>
                <p>Browse products and add your favorite fashion items.</p>
                <a href="shop.php" class="btn">Go to Shop</a>
            </div>
        <?php else: ?>
            <form method="POST" action="cart.php">
                <div class="cart-layout-modern">
                    <div class="cart-items-modern">
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $product_id => $quantity):
                            $query = "SELECT * FROM products WHERE product_id = $product_id";
                            $result = mysqli_query($conn, $query);
                            $product = mysqli_fetch_assoc($result);

                            if (!$product) continue;

                            $subtotal = $product['price'] * $quantity;
                            $total += $subtotal;
                        ?>
                        <div class="cart-item-card">
                            <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">
                            <div class="cart-item-info">
                                <h3><?php echo $product['product_name']; ?></h3>
                                <p><?php echo $product['description']; ?></p>
                                <span class="cart-item-price">Rs. <?php echo number_format($product['price'], 2); ?></span>
                            </div>

                            <div class="cart-item-controls">
                                <label>Qty</label>
                                <input type="number" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="1" class="modern-qty-input">
                            </div>

                            <div class="cart-item-subtotal">
                                <p>Subtotal</p>
                                <strong>Rs. <?php echo number_format($subtotal, 2); ?></strong>
                            </div>

                            <div class="cart-item-remove">
                                <a href="cart.php?remove=<?php echo $product_id; ?>" class="remove-btn-modern">Remove</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="cart-summary-modern">
                        <h2>Order Summary</h2>
                        <div class="summary-row-modern">
                            <span>Total Items</span>
                            <span><?php echo count($_SESSION['cart']); ?></span>
                        </div>
                        <div class="summary-row-modern">
                            <span>Total Amount</span>
                            <strong>Rs. <?php echo number_format($total, 2); ?></strong>
                        </div>

                        <div class="cart-summary-actions">
                            <button type="submit" name="update_cart" class="btn">Update Cart</button>
                            <a href="checkout.php" class="btn secondary-btn">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </main>
</div>

</body>
</html>