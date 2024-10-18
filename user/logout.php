<<<<<<< HEAD
<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../index.php"); // Redirect to normal user login page
exit();
?>
=======
<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../index.php"); // Redirect to normal user login page
exit();
?>
>>>>>>> b04dae7a9d44575933dc0c0f6ef591db89fab706
