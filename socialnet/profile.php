<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT username, fullname, gender, description FROM account WHERE username='" . $username . "'";
$rows = db_query($sql);
$profile = $rows ? $rows->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Social Network</title>
    <style>
        body { font-family: sans-serif; margin: 0; background-color: #f4f4f9; }
        .content { padding: 20px; }
        .profile-card {
            max-width: 520px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 18px;
            margin-top: 20px;
        }
        .profile-row { margin: 10px 0; }
        .label { font-weight: 700; display: inline-block; width: 120px; }
        .empty { color: #777; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <h2>My Profile</h2>

        <?php if ($profile): ?>
            <div class="profile-card">
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