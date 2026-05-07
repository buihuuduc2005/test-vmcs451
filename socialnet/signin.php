<?php
session_start();
require_once('db.php');
require_once('flash.php');

// Handling the form POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $username_escaped = escape_sql($username);
    $sql_stmt = "SELECT password FROM account WHERE username='" . $username_escaped . "'";
    $rows = db_query($sql_stmt);
    
    $first_row = $rows->fetch_assoc();
    if (!$first_row) {
        set_flash('error', 'Invalid username or password.');
        header('Location: signin.php');
        exit();
    } 
    else {
        $hash_password = $first_row['password'];
        if ( password_verify($password, $hash_password) ) {
            // Redirect user to home.php
            $_SESSION['username'] = $username;
            header('Location: home.php');
            exit(); 
        } else {
            set_flash('error', 'Invalid username or password.');
            header('Location: signin.php');
            exit();
        }
    }
}

$flash = consume_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In Form</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">
    <form class="card auth-form" action="signin.php" method="POST">
        <h2>Sign In</h2>
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>"><?php echo htmlspecialchars($flash['message']); ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Login" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn block">Sign in</button>
    </form>
</body>
</html>
