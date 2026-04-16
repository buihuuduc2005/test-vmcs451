<?php
// mini API example: /api.php?name=Duc

header("Content-Type: application/json");

// fake database
$users = [
    "duc" => ["age" => 21, "role" => "student"],
    "sonic" => ["age" => 2, "role" => "hedgehog"],
    "admin" => ["age" => 30, "role" => "admin"]
];

// get query param
$name = isset($_GET['name']) ? strtolower($_GET['name']) : null;

if ($name && array_key_exists($name, $users)) {
    echo json_encode([
        "status" => "success",
        "data" => [
            "name" => $name,
            "info" => $users[$name]
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "User not found",
        "available_users" => array_keys($users)
    ]);
}
?>