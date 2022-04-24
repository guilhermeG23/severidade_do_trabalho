<?php
session_start();
if (empty($_SESSION['id_user']) || empty($_SESSION['usuario_banco']) || empty($_SESSION['senha_banco']) || empty($_SESSION['limite'])) {
    header("Location: ./logout.php");
}

if (date_timestamp_get(date_create()) >= $_SESSION['limite']) {
    header("Location: ./logout.php");
}