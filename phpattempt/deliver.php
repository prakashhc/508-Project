<?php
session_start();

if (isset($_SESSION['user_id'])) {
    include 'deliver.html';
} else {
    header("Location: signin.php");
    exit();
}