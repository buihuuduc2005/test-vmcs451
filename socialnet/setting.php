<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

require_once('db.php');
require_once('flash.php');
$me = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    db_query("UPDATE account SET description='" . $description . "' WHERE username='" . $me . "'");
    set_flash('success', 'Profile description updated.');
    header('Location: setting.php');
    exit();
}

$rows = db_query("SELECT description FROM account WHERE username='" . $me . "'");
$old_description = '';
if ($rows && $rows->num_rows > 0) {
    $old_description = $rows->fetch_assoc()['description'];
}

$flash = consume_flash();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SocialNet: Settings</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <h1>Settings</h1>
        <?php if ($flash): ?>
            <p class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>"><?php echo htmlspecialchars($flash['message']); ?></p>
        <?php endif; ?>

        <form class="card" method="POST" action="setting.php">
            <label for="description">Profile description</label>
            <textarea id="description" name="description" placeholder="Tell people about yourself..."><?php echo htmlspecialchars($old_description); ?></textarea>
            <button type="submit" class="btn">Save</button>
        </form>
    </div>
</body>
</html>
