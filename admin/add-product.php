<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$categories = mysqli_query($conn, "SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = intval($_POST['category_id']);
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $image_name = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target = '../assets/images/' . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $query = "INSERT INTO products (category_id, product_name, description, gender, size, color, price, stock, image)
              VALUES ('$category_id', '$product_name', '$description', '$gender', '$size', '$color', '$price', '$stock', '$image_name')";

    if (mysqli_query($conn, $query)) {
        $message = "Product added successfully!";
    } else {
        $message = "Failed to add product.";
    }
}

include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h1>Add Product</h1>
    <a href="manage-products.php" class="btn secondary-btn">← Back to Products</a>
</div>

<div class="admin-card">
    <?php if ($message): ?>
        <div class="checkout-message-box"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="admin-form-grid">
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php while ($cat = mysqli_fetch_assoc($categories)) : ?>
                <option value="<?php echo $cat['category_id']; ?>">
                    <?php echo $cat['category_name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <input type="text" name="product_name" placeholder="Product Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="text" name="gender" placeholder="Gender (Men/Women)" required>
        <input type="text" name="size" placeholder="Size (S,M,L or Free Size)" required>
        <input type="text" name="color" placeholder="Color" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="number" name="stock" placeholder="Stock Quantity" required>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" class="btn">Add Product</button>
    </form>
</div>

<?php include 'includes/admin-footer.php'; ?>