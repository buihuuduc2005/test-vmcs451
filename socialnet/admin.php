<?php
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
        echo "<h3 style='color: green; text-align: center;'>Account created successfully for: $username</h3>";
    } else {
        echo "<h3 style='color: red; text-align: center;'>Error: " . mysqli_error($conn) . "</h3>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Create Account</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 20px; }
        form { background: white; padding: 20px; border: 1px solid #ccc; max-width: 300px; width: 100%; }
        input, select { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
        .btn { background-color: #04AA6D; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; margin-top: 10px; }
    </style>
</head>
<body>
    <form action="admin.php" method="POST">
        <h2>Create New User</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="fullname" placeholder="Full Name" required>
        <select name="gender" required>
            <option value="" disabled selected>Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <button type="submit" class="btn">Create Account</button>
    </form>
</body>
</html>
