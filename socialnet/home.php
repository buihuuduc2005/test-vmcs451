<?php
session_start();
require_once('db.php');

// 1. Security Check: Redirect to login if session is empty
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

$me = $_SESSION['username'];


// We select id, username, fullname, and description to show on the cards
$me_escaped = escape_sql($me);
$sql = "SELECT id, username, fullname, description, picture FROM account WHERE username != '" . $me_escaped . "'";
$rows = db_query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Social Network</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .home-title {
            margin-bottom: 30px;
        }
        
        .home-title h2 {
            margin: 0 0 5px 0;
            color: var(--text);
        }
        
        .home-title p {
            color: var(--muted);
            margin: 5px 0;
        }
        
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .user-card-wrapper {
            display: flex;
            flex-direction: column;
        }
        
        .user-card {
            border: 1px solid var(--border);
            background: var(--card-bg);
            padding: 0;
            margin: 0;
            border-radius: 8px;
            overflow: hidden;
            flex: 1;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s, transform 0.3s;
        }
        
        .user-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .user-card img {
            display: block;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .user-card-content {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .user-card h3 {
            margin: 0 0 8px 0;
            color: var(--text);
            font-size: 1.1em;
        }
        
        .user-card p {
            margin: 0 0 15px 0;
            color: var(--muted);
            font-size: 0.95em;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .view-profile-btn {
            background-color: var(--accent);
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            transition: background-color 0.3s;
            font-size: 0.95em;
        }
        
        .view-profile-btn:hover {
            background-color: var(--accent-dark);
        }
        
        .empty-message {
            text-align: center;
            padding: 40px 20px;
            color: var(--muted);
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="home-title">
            <h2>👋 Welcome to Social Network, <?php echo htmlspecialchars($me); ?>!</h2>
            <p>Discover and connect with other users</p>
        </div>

        <?php if ($rows && $rows->num_rows > 0): ?>
            <div class="users-grid">
                <?php while ($user = $rows->fetch_assoc()): ?>
                    <div class="user-card-wrapper">
                        <div class="user-card">
                            <?php if (!empty($user['picture']) && file_exists($user['picture'])): ?>
                                <img src="<?php echo htmlspecialchars($user['picture']); ?>" alt="<?php echo htmlspecialchars($user['fullname']); ?>">
                            <?php else: ?>
                                <div style="width: 100%; height: 200px; background-color: #111822; display: flex; align-items: center; justify-content: center; color: #9aa3ad;">
                                    <span>No Picture</span>
                                </div>
                            <?php endif; ?>
                            <div class="user-card-content">
                                <h3><?php echo htmlspecialchars($user['fullname']); ?></h3>
                                <p style="color: #9aa3ad; font-size: 0.9em; margin-bottom: 10px;">@<?php echo htmlspecialchars($user['username']); ?></p>
                                <p><?php echo htmlspecialchars($user['description']); ?></p>
                                <a href="view-profile.php?user=<?php echo urlencode($user['username']); ?>" class="view-profile-btn">View Profile</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-message">
                <p>No other users found yet.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
