<?php
session_start();
require_once('db.php');

// 1. Security Check: Redirect to login if session is empty
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

$me = $_SESSION['username'];

// 2. Fetch the "List of Strangers" (Everyone except me)
// We select id, username, fullname, and description to show on the cards
$sql = "SELECT id, username, fullname, description FROM account WHERE username != '$me'";
$rows = db_query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Social Network</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <h2>Welcome to the Social Network, <?php echo htmlspecialchars($me); ?>!</h2>
        <p>You have successfully logged in and an active session has started.</p>

        <?php if ($rows && $rows->num_rows > 0): ?>
            <?php while ($user = $rows->fetch_assoc()): ?>
                <div class="user-card">
                    <h3><?php echo htmlspecialchars($user['fullname']); ?> (@<?php echo htmlspecialchars($user['username']); ?>)</h3>
                    <p><?php echo htmlspecialchars($user['description']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty">No other users found.</p>
        <?php endif; ?>
    </div>

</body>
</html>
