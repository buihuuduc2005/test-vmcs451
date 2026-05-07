<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

$username = $_SESSION['username'];
$username_escaped = escape_sql($username);
$sql = "SELECT username, fullname, gender, description, picture FROM account WHERE username='" . $username_escaped . "'";
$rows = db_query($sql);
$profile = $rows ? $rows->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Social Network</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <h2>My Profile</h2>

        <?php if ($profile): ?>
            <div class="profile-card card">
                <?php if (!empty($profile['picture']) && file_exists($profile['picture'])): ?>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="<?php echo htmlspecialchars($profile['picture']); ?>" alt="Profile picture" style="max-width: 300px; max-height: 300px; border-radius: 8px; object-fit: cover;">
                    </div>
                <?php endif; ?>
                <div class="profile-row"><span class="label">Username:</span> <?php echo htmlspecialchars($profile['username']); ?></div>
                <div class="profile-row"><span class="label">Full name:</span> <?php echo htmlspecialchars($profile['fullname']); ?></div>
                <div class="profile-row"><span class="label">Gender:</span> <?php echo htmlspecialchars($profile['gender']); ?></div>
                <div class="profile-row"><span class="label">Description:</span>
                    <?php if (!empty($profile['description'])): ?>
                        <?php echo nl2br(htmlspecialchars($profile['description'])); ?>
                    <?php else: ?>
                        <span class="empty">No description yet. Update it in Settings.</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <p>Unable to load your profile right now.</p>
        <?php endif; ?>
    </div>
</body>
</html>