<?php
// Encerra o login do administrador
session_start();
$_SESSION = [];
session_destroy();
header('Location: admin.php');
exit;
