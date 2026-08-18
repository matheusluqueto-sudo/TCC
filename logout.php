<?php
session_start();
unset($_SESSION['usuariologado']);
session_destroy();
header('Location: login.php');
exit;
?>