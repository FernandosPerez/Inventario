<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

// Verificar que haya sesión activa
if (empty($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}
?>