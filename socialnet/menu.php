<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<style>
    ul.navbar {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background-color: #333;
    }

    ul.navbar li {
        float: left;
    }

    ul.navbar li a {
        display: block;
        color: #fff;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    ul.navbar li a:hover {
        background-color: #04AA6D;
    }

    ul.navbar li a.active {
        background-color: #04AA6D;
        font-weight: 700;
    }

    ul.navbar li.right {
        float: right;
    }

    ul.navbar li.welcome {
        float: right;
        color: #ddd;
        padding: 14px 16px;
    }
</style>

<ul class="navbar">
    <li><a class="<?php echo $currentPage === 'home.php' ? 'active' : ''; ?>" href="home.php">Home</a></li>
    <li><a class="<?php echo $currentPage === 'setting.php' ? 'active' : ''; ?>" href="setting.php">Settings</a></li>
    <li><a class="<?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>" href="profile.php">Profile</a></li>

    <?php if ($currentUser): ?>
        <li class="right"><a href="signout.php">Sign out</a></li>
        <li class="welcome">Hi, <?php echo htmlspecialchars($currentUser); ?></li>
    <?php else: ?>
        <li class="right"><a href="signin.php">Sign in</a></li>
    <?php endif; ?>
</ul>