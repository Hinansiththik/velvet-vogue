<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE product_id = $product_id");
    header("Location: manage-products.php");
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';

$query = "SELECT products.*, categories.category_name 
          FROM products 
          LEFT JOIN categories ON products.category_id = categories.category_id";

if ($search !== '') {
    $query .= " WHERE products.product_name LIKE '%$search%' 
                OR categories.category_name LIKE '%$search%' 
                OR products.gender LIKE '%$search%' 
                OR products.color LIKE '%$search%'";
}

$query .= " ORDER BY products.product_id DESC";

$result = mysqli_query($conn, $query);

include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<div class="admin-page-header">
    <h1>Manage Products</h1>

    <div class="admin-header-actions">
        <form method="GET" action="manage-products.php" class="admin-search-form">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn small-green-btn">Search</button>
            <a href="manage-products.php" class="btn secondary-btn small-green-btn">Reset</a>
        </form>

        <a href="add-product.php" class="btn">Add New Product</a>
    </div>
</div>

<div class="admin-table-box">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Gender</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['product_id']; ?></td>
                    <td>
                        <img src="../assets/images/<?php echo $row['image']; ?>" width="70" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    </td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                    <td>Rs. <?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>
                        <a href="edit-product.php?id=<?php echo $row['product_id']; ?>" class="btn small-green-btn">Edit</a>
                        <a href="manage-products.php?delete=<?php echo $row['product_id']; ?>" class="btn secondary-btn small-green-btn" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/admin-footer.php'; ?>