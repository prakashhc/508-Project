<?php
// Display all errors, very useful for PHP debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Parameters of the MySQL connection 
$servername = "cmsc508.com";
$username = "24SP_chatlanipr";
$password = "24SP_chatlanipr";
$database = "24SP_chatlanipr_pr";

try {
    // Establish a connection with the MySQL server
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit(); // Terminate script execution on connection failure
}

// Start or resume session variables
session_start();

// If the user_ID session is not set, then the user has not logged in yet
if (!isset($_SESSION['user_ID'])) {
    // Redirect to signin.php if the current page is not signin.php
    if (basename($_SERVER['PHP_SELF']) != 'signin.php') {
        header("Location: signin.php");
        exit();
    }
}

?>
