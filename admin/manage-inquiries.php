<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM inquiries WHERE inquiry_id = $id");
    header("Location: manage-inquiries.php");
    exit;
}

if (isset($_GET['mark_replied'])) {
    $id = intval($_GET['mark_replied']);
    mysqli_query($conn, "UPDATE inquiries SET reply_status = 'Replied' WHERE inquiry_id = $id");
    header("Location: manage-inquiries.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM inquiries ORDER BY inquiry_id DESC");

include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<div class="admin-page-header">
    <h1>Manage Inquiries</h1>
</div>

<div class="admin-table-box">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                <th>Reply</th>
                <th>Mark</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : 
                $subject = rawurlencode("Reply from Velvet Vogue");
                $body = rawurlencode(
                    "Dear " . $row['name'] . ",\n\n" .
                    "Thank you for contacting Velvet Vogue.\n\n" .
                    "Regarding your inquiry:\n\"" . $row['message'] . "\"\n\n" .
                    "Our response:\n\n" .
                    "[Type your reply here]\n\n" .
                    "Best regards,\nVelvet Vogue Support Team"
                );
            ?>
                <tr>
                    <td><?php echo $row['inquiry_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td style="max-width: 280px;"><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <span class="reply-status-badge status-<?php echo strtolower($row['reply_status']); ?>">
                            <?php echo htmlspecialchars($row['reply_status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>?subject=<?php echo $subject; ?>&body=<?php echo $body; ?>"
                           class="btn small-green-btn">
                           Reply
                        </a>
                    </td>
                    <td>
                        <?php if ($row['reply_status'] === 'Pending') : ?>
                            <a href="manage-inquiries.php?mark_replied=<?php echo $row['inquiry_id']; ?>"
                               class="btn small-green-btn">
                               Mark Replied
                            </a>
                        <?php else : ?>
                            <span class="done-text">Done</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="manage-inquiries.php?delete=<?php echo $row['inquiry_id']; ?>"
                           class="btn secondary-btn small-green-btn"
                           onclick="return confirm('Delete this inquiry?')">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/admin-footer.php'; ?>