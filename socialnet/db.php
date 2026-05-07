<?php
// A reusable function to handle all database connections and queries
function db_connect() {
    $hosts = array("socialnet_db", "socialnet");

    foreach ($hosts as $database) {
        try {
            $conn = mysqli_connect("127.0.0.1", "admin", "Abc123", $database);
            if ($conn) {
                return $conn;
            }
        } catch (mysqli_sql_exception $exception) {
            continue;
        }
    }

    die("Database connection failed: unable to connect to either socialnet_db or socialnet.");
}

function db_query($query) {
    // 1. Connect to the database
    $conn = db_connect();
    
    // 2. Run the query and return the result
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);
    return $result;
}

// Helper function to escape strings for SQL queries
function escape_sql($value) {
    $conn = db_connect();
    $escaped = mysqli_real_escape_string($conn, $value);
    mysqli_close($conn);
    return $escaped;
}
?>
