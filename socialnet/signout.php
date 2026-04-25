<?php
session_start();

// Clear all session data first, then destroy session.
$_SESSION = array();
session_destroy();

header('Location: signin.php');
exit();

