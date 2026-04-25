<?php
require_once('db.php');

// Handling the form POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql_stmt = "SELECT password FROM account WHERE username='" . $_POST['username'] . "'";
    $rows = db_query($sql_stmt);
    
    $first_row = $rows->fetch_assoc();
    if (!$first_row) {
        print("User " . $_POST['username'] . " not found");
    } 
    else {
        $hash_password = $first_row['password'];
        if ( password_verify($_POST['password'], $hash_password) ) {
            // Redirect user to home.php
            session_start();
            $_SESSION['username'] = $_POST['username'];
            header('Location: home.php');
            exit(); 
        } else {
            print("Wrong password");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In Form</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 20px; }
        form { background: white; padding: 20px; border: 1px solid #ccc; max-width: 300px; width: 100%; }
        input { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
        .btn { background-color: #04AA6D; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; }
    </style>
</head>
<body>
    <form action="signin.php" method="POST">
        <h2>Sign In</h2>
        <input type="text" name="username" placeholder="Login" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Sign in</button>
    </form>
</body>
</html>
