<?php
include 'includes/db.php';

$gender = isset($_GET['gender']) ? mysqli_real_escape_string($conn, $_GET['gender']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$size = isset($_GET['size']) ? mysqli_real_escape_string($conn, $_GET['size']) : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;

$query = "SELECT * FROM products WHERE 1=1";

if ($gender != '') {
    $query .= " AND gender = '$gender'";
}
if ($category > 0) {
    $query .= " AND category_id = $category";
}
if ($size != '') {
    $query .= " AND size LIKE '%$size%'";
}
if ($min_price > 0) {
    $query .= " AND price >= $min_price";
}
if ($max_price > 0) {
    $query .= " AND price <= $max_price";
}

$result = mysqli_query($conn, $query);
$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Velvet Vogue</title>
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
                <h1>Shop Products</h1>
                <p>Find your perfect fashion item</p>
            </div>

            <div class="top-actions">
                <input type="text" placeholder="Search Product" class="search-box">
                <a href="login.php" class="icon-btn">❤</a>
                <a href="cart.php" class="icon-btn">🛒</a>
                <a href="account.php" class="icon-btn">👤</a>
            </div>
        </div>

        <div class="filter-box modern-filter-box">
            <form method="GET" action="shop.php" class="filter-form">
    <select name="gender">
        <option value="">All Genders</option>
        <option value="Men" <?php if ($gender == 'Men') echo 'selected'; ?>>Men</option>
        <option value="Women" <?php if ($gender == 'Women') echo 'selected'; ?>>Women</option>
    </select>

    <select name="category">
        <option value="0">All Categories</option>
        <?php while ($cat = mysqli_fetch_assoc($categories)) : ?>
            <option value="<?php echo $cat['category_id']; ?>" <?php if ($category == $cat['category_id']) echo 'selected'; ?>>
                <?php echo $cat['category_name']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select name="size">
        <option value="">All Sizes</option>
        <option value="S" <?php if ($size == 'S') echo 'selected'; ?>>S</option>
        <option value="M" <?php if ($size == 'M') echo 'selected'; ?>>M</option>
        <option value="L" <?php if ($size == 'L') echo 'selected'; ?>>L</option>
        <option value="XL" <?php if ($size == 'XL') echo 'selected'; ?>>XL</option>
        <option value="Free Size" <?php if ($size == 'Free Size') echo 'selected'; ?>>Free Size</option>
    </select>

    <input type="number" step="0.01" name="min_price" placeholder="Min Price" value="<?php echo $min_price > 0 ? $min_price : ''; ?>">
    <input type="number" step="0.01" name="max_price" placeholder="Max Price" value="<?php echo $max_price > 0 ? $max_price : ''; ?>">

    <button type="submit" class="btn">Filter</button>
    <a href="shop.php" class="btn secondary-btn">Reset</a>
</form>
        </div>

        <section class="product-row-section">
            <h2 class="section-heading">Available Products</h2>

            <div class="shop-grid">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
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
                <?php else: ?>
                    <p>No products found for selected filters.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

</body>
</html>