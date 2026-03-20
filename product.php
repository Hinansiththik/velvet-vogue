<?php
session_start();
include 'includes/db.php';

if (!isset($_GET['id'])) {
    echo "Product not found.";
    exit;
}

$product_id = intval($_GET['id']);
$query = "SELECT * FROM products WHERE product_id = $product_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Product not found.";
    exit;
}

$product = mysqli_fetch_assoc($result);

$related_query = "SELECT * FROM products WHERE product_id != $product_id ORDER BY product_id DESC LIMIT 4";
$related_result = mysqli_query($conn, $related_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['product_name']; ?> - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="brand">Velvet<br>Vogue</div>

        <ul class="sidebar-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php" class="active">Explore</a></li>
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
                <h1>Product Details</h1>
                <p>See product information before purchase</p>
            </div>

            <div class="top-actions">
                <input type="text" placeholder="Search Product" class="search-box">
                <a href="login.php" class="icon-btn">❤</a>
                <a href="cart.php" class="icon-btn">🛒</a>
                <a href="account.php" class="icon-btn">👤</a>
            </div>
        </div>

        <div class="modern-product-details">
            <div class="modern-product-image">
                <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">
            </div>

            <div class="modern-product-info">
                <h2><?php echo $product['product_name']; ?></h2>
                <p class="modern-rating">★★★★★ <span>(Fashion Collection)</span></p>
                <p class="modern-description"><?php echo $product['description']; ?></p>

                <h3 class="modern-price">Rs. <?php echo number_format($product['price'], 2); ?></h3>

                <div class="modern-meta">
                    <p><strong>Gender:</strong> <?php echo $product['gender']; ?></p>
                    <p><strong>Size:</strong> <?php echo $product['size']; ?></p>
                    <p><strong>Color:</strong> <?php echo $product['color']; ?></p>
                    <p><strong>Stock:</strong> <?php echo $product['stock']; ?></p>
                </div>

               <form action="cart.php" method="GET" class="modern-cart-form">
    <input type="hidden" name="add" value="<?php echo $product['product_id']; ?>">
    <input type="number" name="quantity" value="1" min="1" class="modern-qty-input">
    <button type="submit" class="btn">Add to Cart</button>
    <button type="submit" name="buy_now" value="1" class="btn secondary-btn">Buy Now</button>
</form>
            </div>
        </div>

        <section class="product-row-section">
            <h2 class="section-heading">Related Products</h2>

            <div class="shop-grid">
                <?php while ($row = mysqli_fetch_assoc($related_result)) : ?>
                    <div class="mini-product-card shop-card">
                        <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>">
                        <h3><?php echo $row['product_name']; ?></h3>
                        <p><?php echo $row['description']; ?></p>
                        <div class="shop-card-bottom">
                            <span class="shop-price">Rs. <?php echo number_format($row['price'], 2); ?></span>
                            <a href="product.php?id=<?php echo $row['product_id']; ?>" class="btn small-green-btn">View</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
</div>

</body>
</html>