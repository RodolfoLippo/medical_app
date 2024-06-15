<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['language'])) {
    echo json_encode(['language' => $_SESSION['language']]);
} else {
    echo json_encode(['language' => 'es']);
}
?>
