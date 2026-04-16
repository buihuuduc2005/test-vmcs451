<?php
$name = $_GET['name'] ?? "Guest";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple PHP</title>
</head>
<body>
    <h1>Hello, <?php echo $name; ?>!</h1>
</body>
</html>
