<?php
session_start();
require_once('flash.php');
// Handling the form POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Connect to MySQL
    $conn = mysqli_connect("127.0.0.1", "admin", "Abc123", "socialnet_db");

    // 2. Grab the data from the HTML form
    $username = $_POST['username'];
    $plaintext_password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $gender = $_POST['gender'];

    // 3. SECURE THE PASSWORD (This creates the 60-character hash)
    $hash_password = password_hash($plaintext_password, PASSWORD_DEFAULT);

    // 4. Prepare the SQL query to insert the new user
    $sql_stmt = "INSERT INTO account (username, password, fullname, gender) 
                 VALUES ('$username', '$hash_password', '$fullname', '$gender')";
    
    // 5. Execute the query and print a success message
    if (mysqli_query($conn, $sql_stmt)) {
        set_flash('success', "Account created successfully for: $username");
    } else {
        set_flash('error', "Error: " . mysqli_error($conn));
    }

    header('Location: admin.php');
    exit();
}

$flash = consume_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Create Account</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">
    <form class="card auth-form" action="admin.php" method="POST">
        <h2>Create New User</h2>
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>"><?php echo htmlspecialchars($flash['message']); ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="fullname" placeholder="Full Name" required>
        <select name="gender" required>
            <option value="" disabled selected>Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <button type="submit" class="btn block">Create Account</button>
    </form>
</body>
</html>
