<?php
session_start();
include 'includes/db.php';

if (!isset($_GET['order_id'])) {
    echo "Receipt not found.";
    exit;
}

$order_id = intval($_GET['order_id']);

$order_query = "
    SELECT orders.*, users.full_name, users.email, users.phone, users.address
    FROM orders
    LEFT JOIN users ON orders.user_id = users.user_id
    WHERE orders.order_id = $order_id
";
$order_result = mysqli_query($conn, $order_query);

if (!$order_result || mysqli_num_rows($order_result) == 0) {
    echo "Receipt not found.";
    exit;
}

$order = mysqli_fetch_assoc($order_result);

$items_query = "
    SELECT order_items.*, products.product_name
    FROM order_items
    LEFT JOIN products ON order_items.product_id = products.product_id
    WHERE order_items.order_id = $order_id
";
$items_result = mysqli_query($conn, $items_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="receipt-page">
    <div class="receipt-box">
        <div class="receipt-header">
            <div>
                <h1>Velvet Vogue</h1>
                <p>Official Payment Receipt</p>
            </div>
            <div class="receipt-order-info">
                <p><strong>Order ID:</strong> #<?php echo $order['order_id']; ?></p>
                <p><strong>Date:</strong> <?php echo $order['created_at']; ?></p>
                <p><strong>Status:</strong> <?php echo $order['order_status']; ?></p>
            </div>
        </div>

        <div class="receipt-customer">
            <h2>Customer Details</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
        </div>

        <div class="receipt-items">
            <h2>Order Items</h2>
            <table class="receipt-table">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>

                <?php while ($item = mysqli_fetch_assoc($items_result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                        <td>Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="receipt-total">
            <h2>Total Amount: Rs. <?php echo number_format($order['total_amount'], 2); ?></h2>
        </div>

        <div class="receipt-footer">
            <p>Thank you for shopping with Velvet Vogue.</p>
            <p>This is a computer-generated receipt.</p>
        </div>

        <div class="receipt-actions no-print">
            <button onclick="window.print()" class="btn">Print Receipt</button>
            <a href="shop.php" class="btn secondary-btn">Continue Shopping</a>
        </div>
    </div>
</div>

</body>
</html>