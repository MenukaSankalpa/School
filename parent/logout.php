<?php
session_start();
session_unset(); // clear all session variables
session_destroy(); // destroy session
header("Location: ../login.php"); // redirect to login page
exit();
