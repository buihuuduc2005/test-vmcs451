<?php
// A reusable function to handle all database connections and queries
function db_query($query) {
    // 1. Connect to the database
    $conn = mysqli_connect("127.0.0.1", "admin", "Abc123", "socialnet_db");
    
    // 2. Run the query and return the result
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);
    return $result;
}

// Helper function to escape strings for SQL queries
function escape_sql($value) {
    $conn = mysqli_connect("127.0.0.1", "admin", "Abc123", "socialnet_db");
    $escaped = mysqli_real_escape_string($conn, $value);
    mysqli_close($conn);
    return $escaped;
}
?>
