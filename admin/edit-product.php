<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: manage-products.php");
    exit;
}

$product_id = intval($_GET['id']);
$message = "";

$product_query = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $product_id");
$product = mysqli_fetch_assoc($product_query);

if (!$product) {
    header("Location: manage-products.php");
    exit;
}

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

    $image_name = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target = '../assets/images/' . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $update_query = "UPDATE products SET
                        category_id = '$category_id',
                        product_name = '$product_name',
                        description = '$description',
                        gender = '$gender',
                        size = '$size',
                        color = '$color',
                        price = '$price',
                        stock = '$stock',
                        image = '$image_name'
                     WHERE product_id = $product_id";

    if (mysqli_query($conn, $update_query)) {
        $message = "Product updated successfully!";
        $product_query = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $product_id");
        $product = mysqli_fetch_assoc($product_query);
    } else {
        $message = "Failed to update product.";
    }
}

include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<div class="admin-page-header">
    <h1>Edit Product</h1>
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
                <option value="<?php echo $cat['category_id']; ?>"
                    <?php echo ($cat['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
                    <?php echo $cat['category_name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        <input type="text" name="gender" value="<?php echo htmlspecialchars($product['gender']); ?>" required>
        <input type="text" name="size" value="<?php echo htmlspecialchars($product['size']); ?>" required>
        <input type="text" name="color" value="<?php echo htmlspecialchars($product['color']); ?>" required>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

        <div class="admin-current-image">
            <p><strong>Current Image:</strong></p>
            <?php if (!empty($product['image'])): ?>
                <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="Current Product Image" width="120">
            <?php else: ?>
                <p>No image uploaded.</p>
            <?php endif; ?>
        </div>

        <input type="file" name="image" accept="image/*">

        <button type="submit" class="btn">Update Product</button>
    </form>
</div>

<?php include 'includes/admin-footer.php'; ?>