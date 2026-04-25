<?php
// A reusable function to handle all database connections and queries
function db_query($query) {
    // 1. Connect to the database
    $conn = mysqli_connect("127.0.0.1", "admin", "Abc123", "socialnet_db");
    
    // 2. Run the query and return the result
    $result = mysqli_query($conn, $query);
    return $result;
}
?>
