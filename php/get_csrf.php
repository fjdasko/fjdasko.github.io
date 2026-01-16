<?php
// get_csrf.php - vygeneruje CSRF token v session a vráti ho ako JSON
session_start();
header('Content-Type: application/json; charset=utf-8');
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
echo json_encode(['csrf_token' => $_SESSION['csrf_token']]);

