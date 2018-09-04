<?php
// Vikram Sunil Bajaj (vsb259), Ameya Shanbhag (avs431)
error_reporting(0);

session_start(); //to ensure you are using the same session
session_destroy(); //destroy the session
header("Location: login.php"); //to redirect back to "login.php" after logging out
exit();
?>
