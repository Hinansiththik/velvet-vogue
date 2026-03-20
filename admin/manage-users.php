<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';

$users_query = "SELECT * FROM users";

if ($search !== '') {
    $users_query .= " WHERE full_name LIKE '%$search%' 
                      OR email LIKE '%$search%' 
                      OR phone LIKE '%$search%' 
                      OR address LIKE '%$search%'";
}

$users_query .= " ORDER BY user_id DESC";

$users_result = mysqli_query($conn, $users_query);

include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<div class="admin-page-header">
    <h1>Manage Users</h1>

    <div class="admin-header-actions">
        <form method="GET" action="manage-users.php" class="admin-search-form">
            <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn small-green-btn">Search</button>
            <a href="manage-users.php" class="btn secondary-btn small-green-btn">Reset</a>
        </form>
    </div>
</div>

<div class="admin-table-box">
    <table class="admin-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = mysqli_fetch_assoc($users_result)) : ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td><?php echo htmlspecialchars($user['address']); ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/admin-footer.php'; ?>