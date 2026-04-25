<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

require_once('db.php');
$me = $_SESSION['username'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    db_query("UPDATE account SET description='" . $description . "' WHERE username='" . $me . "'");
    $message = 'Profile description updated.';
}

$rows = db_query("SELECT description FROM account WHERE username='" . $me . "'");
$old_description = '';
if ($rows && $rows->num_rows > 0) {
    $old_description = $rows->fetch_assoc()['description'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SocialNet: Settings</title>
    <style>
        body { font-family: sans-serif; margin: 0; background-color: #f4f4f9; }
        .content { padding: 20px; }
        form {
            max-width: 540px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
        }
        textarea {
            width: 100%;
            box-sizing: border-box;
            min-height: 120px;
            margin: 8px 0 12px;
        }
        .btn {
            background-color: #04AA6D;
            color: #fff;
            border: none;
            padding: 10px 14px;
            cursor: pointer;
        }
        .message { color: #097d4e; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <h1>Settings</h1>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST" action="setting.php">
            <label for="description">Profile description</label>
            <textarea id="description" name="description" placeholder="Tell people about yourself..."><?php echo htmlspecialchars($old_description); ?></textarea>
            <button type="submit" class="btn">Save</button>
        </form>
    </div>
</body>
</html>
