<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$currentPage = basename($_SERVER['PHP_SELF']);
?>
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