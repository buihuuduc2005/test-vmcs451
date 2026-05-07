<?php
session_start();
require_once('db.php');

// Security check: redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

// Get the username from URL parameter
$view_username = isset($_GET['user']) ? trim($_GET['user']) : '';

if (empty($view_username)) {
    header('Location: home.php');
    exit();
}

// Prevent viewing own profile here (should use profile.php instead)
$current_user = $_SESSION['username'];
if ($view_username === $current_user) {
    header('Location: profile.php');
    exit();
}

// Fetch user profile
$view_username_escaped = escape_sql($view_username);
$sql = "SELECT username, fullname, gender, description, picture FROM account WHERE username='" . $view_username_escaped . "'";
$rows = db_query($sql);
$profile = $rows ? $rows->fetch_assoc() : null;

// If user not found, redirect
if (!$profile) {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($profile['fullname']); ?> - Social Network</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .profile-header h2 {
            margin: 0;
            flex: 1;
            min-width: 200px;
        }
        
        .back-button {
            padding: 10px 16px;
            background-color: #666;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.95em;
            transition: background-color 0.3s;
        }
        
        .back-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="profile-header">
            <h2><?php echo htmlspecialchars($profile['fullname']); ?>'s Profile</h2>
            <a href="home.php" class="back-button">← Back to Users</a>
        </div>

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
                        <span class="empty">No description provided.</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <p>Unable to load this user's profile.</p>
        <?php endif; ?>
    </div>
</body>
</html>
