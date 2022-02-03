<?php
// Starter sessionen
session_start();
 
// Unsetter alle session variabler
$_SESSION = array();
 
// Ødelægger sessionen
session_destroy();
 
// videresender til login-siden
header("location: login.php");
exit;
?>
