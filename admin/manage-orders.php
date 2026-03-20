<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['order_status'])) {
    $order_id = intval($_POST['order_id']);
    $order_status = mysqli_real_escape_string($conn, $_POST['order_status']);

    mysqli_query($conn, "UPDATE orders SET order_status = '$order_status' WHERE order_id = $order_id");
    header("Location: manage-orders.php");
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, trim($_GET['status'])) : '';

$orders_query = "SELECT orders.*, users.full_name, users.email
                 FROM orders
                 LEFT JOIN users ON orders.user_id = users.user_id
                 WHERE 1=1";

if ($search !== '') {
    $orders_query .= " AND (
        users.full_name LIKE '%$search%' 
        OR users.email LIKE '%$search%' 
        OR orders.order_id LIKE '%$search%' 
        OR orders.order_status LIKE '%$search%'
    )";
}

if ($status_filter !== '') {
    $orders_query .= " AND orders.order_status = '$status_filter'";
}

$orders_query .= " ORDER BY orders.order_id DESC";

$orders_result = mysqli_query($conn, $orders_query);

include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<div class="admin-page-header">
    <h1>Manage Orders</h1>

    <div class="admin-header-actions">
        <form method="GET" action="manage-orders.php" class="admin-search-form">
            <input type="text" name="search" placeholder="Search orders..." value="<?php echo htmlspecialchars($search); ?>">

            <select name="status" class="admin-select">
                <option value="">All Status</option>
                <option value="Pending" <?php if ($status_filter == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Processing" <?php if ($status_filter == 'Processing') echo 'selected'; ?>>Processing</option>
                <option value="Shipped" <?php if ($status_filter == 'Shipped') echo 'selected'; ?>>Shipped</option>
                <option value="Delivered" <?php if ($status_filter == 'Delivered') echo 'selected'; ?>>Delivered</option>
            </select>

            <button type="submit" class="btn small-green-btn">Apply</button>
            <a href="manage-orders.php" class="btn secondary-btn small-green-btn">Reset</a>
        </form>
    </div>
</div>

<div class="admin-table-box">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Date</th>
                <th>Update</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($orders_result)) : ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                    <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                    <td>
                        <span class="order-status-badge status-<?php echo strtolower($order['order_status']); ?>">
                            <?php echo htmlspecialchars($order['order_status']); ?>
                        </span>
                    </td>
                    <td><?php echo $order['created_at']; ?></td>
                    <td>
                        <form method="POST" action="" class="admin-inline-form">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <select name="order_status" class="admin-select">
                                <option value="Pending" <?php if ($order['order_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Processing" <?php if ($order['order_status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                <option value="Shipped" <?php if ($order['order_status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                <option value="Delivered" <?php if ($order['order_status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                            </select>
                            <button type="submit" class="btn small-green-btn">Save</button>
                        </form>
                    </td>
                    <td>
                        <a href="order-details.php?id=<?php echo $order['order_id']; ?>" class="btn small-green-btn">Details</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/admin-footer.php'; ?>