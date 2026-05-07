<?php
// Database setup script to add picture column if it doesn't exist

$conn = mysqli_connect("127.0.0.1", "admin", "Abc123", "socialnet_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if picture column exists
$result = mysqli_query($conn, "SHOW COLUMNS FROM account LIKE 'picture'");

if (mysqli_num_rows($result) == 0) {
    // Column doesn't exist, add it
    $alter_sql = "ALTER TABLE account ADD COLUMN picture VARCHAR(255) DEFAULT NULL";
    
    if (mysqli_query($conn, $alter_sql)) {
        echo "✓ Successfully added 'picture' column to account table<br>";
    } else {
        echo "✗ Error adding column: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "✓ 'picture' column already exists<br>";
}

mysqli_close($conn);
echo "<br>Setup complete! You can now use profile pictures.";
?>
