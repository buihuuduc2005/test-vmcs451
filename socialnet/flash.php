<?php
function set_flash($type, $message) {
    $_SESSION['flash'] = array(
        'type' => $type,
        'message' => $message
    );
}

function consume_flash() {
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}
?>
