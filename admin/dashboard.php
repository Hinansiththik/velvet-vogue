<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

/* Summary counts */
$product_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM products")
)['total'];

$order_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders")
)['total'];

$user_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)['total'];

$inquiry_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM inquiries")
)['total'];

/* Order status counts */
$pending_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Pending'")
)['total'];

$processing_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Processing'")
)['total'];

$shipped_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Shipped'")
)['total'];

$delivered_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Delivered'")
)['total'];

/* Revenue */
$total_revenue = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders")
)['total'];

/* Prevent divide by zero */
$max_order_status = max($pending_count, $processing_count, $shipped_count, $delivered_count, 1);

/* Recent orders */
$recent_orders = mysqli_query($conn, "
    SELECT orders.order_id, orders.total_amount, orders.order_status, orders.created_at, users.full_name
    FROM orders
    LEFT JOIN users ON orders.user_id = users.user_id
    ORDER BY orders.order_id DESC
    LIMIT 5
");

/* Recent inquiries */
$recent_inquiries = mysqli_query($conn, "
    SELECT inquiry_id, name, email, message, created_at
    FROM inquiries
    ORDER BY inquiry_id DESC
    LIMIT 5
");

include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<div class="admin-page-header">
    <h1>Dashboard</h1>
</div>

<div class="admin-stats-grid">
    <div class="admin-stat-box">
        <h3>Total Products</h3>
        <p><?php echo $product_count; ?></p>
    </div>

    <div class="admin-stat-box">
        <h3>Total Orders</h3>
        <p><?php echo $order_count; ?></p>
    </div>

    <div class="admin-stat-box">
        <h3>Total Users</h3>
        <p><?php echo $user_count; ?></p>
    </div>

    <div class="admin-stat-box">
        <h3>Total Inquiries</h3>
        <p><?php echo $inquiry_count; ?></p>
    </div>
</div>

<div class="admin-chart-grid">
    <div class="admin-card">
        <h2 class="admin-chart-title">Order Status Overview</h2>

        <div class="chart-bars">
            <div class="chart-row">
                <span>Pending</span>
                <div class="chart-bar-wrap">
                    <div class="chart-bar pending-bar" style="width: <?php echo ($pending_count / $max_order_status) * 100; ?>%;"></div>
                </div>
                <strong><?php echo $pending_count; ?></strong>
            </div>

            <div class="chart-row">
                <span>Processing</span>
                <div class="chart-bar-wrap">
                    <div class="chart-bar processing-bar" style="width: <?php echo ($processing_count / $max_order_status) * 100; ?>%;"></div>
                </div>
                <strong><?php echo $processing_count; ?></strong>
            </div>

            <div class="chart-row">
                <span>Shipped</span>
                <div class="chart-bar-wrap">
                    <div class="chart-bar shipped-bar" style="width: <?php echo ($shipped_count / $max_order_status) * 100; ?>%;"></div>
                </div>
                <strong><?php echo $shipped_count; ?></strong>
            </div>

            <div class="chart-row">
                <span>Delivered</span>
                <div class="chart-bar-wrap">
                    <div class="chart-bar delivered-bar" style="width: <?php echo ($delivered_count / $max_order_status) * 100; ?>%;"></div>
                </div>
                <strong><?php echo $delivered_count; ?></strong>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h2 class="admin-chart-title">Revenue Summary</h2>

        <div class="revenue-box">
            <p>Total Revenue</p>
            <h3>Rs. <?php echo number_format($total_revenue, 2); ?></h3>
        </div>

        <div class="summary-mini-grid">
            <div class="summary-mini-box">
                <span>Orders</span>
                <strong><?php echo $order_count; ?></strong>
            </div>
            <div class="summary-mini-box">
                <span>Delivered</span>
                <strong><?php echo $delivered_count; ?></strong>
            </div>
        </div>
    </div>
</div>

<div class="admin-dashboard-grid">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Recent Orders</h2>
            <a href="manage-orders.php" class="btn small-green-btn">View All</a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($recent_orders)) : ?>
                    <tr>
                        <td>#<?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                        <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                        <td>
                            <span class="order-status-badge status-<?php echo strtolower($order['order_status']); ?>">
                                <?php echo htmlspecialchars($order['order_status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Recent Inquiries</h2>
            <a href="manage-inquiries.php" class="btn small-green-btn">View All</a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($inq = mysqli_fetch_assoc($recent_inquiries)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inq['name']); ?></td>
                        <td><?php echo htmlspecialchars($inq['email']); ?></td>
                        <td class="admin-message-cell">
                            <?php echo htmlspecialchars(substr($inq['message'], 0, 60)); ?><?php echo strlen($inq['message']) > 60 ? '...' : ''; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>