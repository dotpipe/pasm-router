<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

include_once('userclass.php');
include_once('pasm.php');
include_once('routes.php');
include_once('crud.php');

// Initialize the Routes object
$route = new Routes();

// Function to add a user
function addUser($route) {
    try {
        // Simulate adding a user
        $_GET['req'] = 'adduser';
        $_GET['from'] = 'localhost';
        $_GET['recv'] = 'localhost';
        $_GET['user'] = 'testuser';
        $_GET['sub'] = 'testsub';
        $_GET['target'] = 'final.php';
        $_GET['port'] = 80;

        // Call the addUserToContract method
        $route->addUserToContract();
        echo "User added successfully.<br>";
    } catch (Exception $e) {
        echo "Error adding user: " . $e->getMessage();
    }
}

// Function to remove a user
function removeUser($route) {
    try {
        // Simulate removing a user
        $_GET['req'] = 'remuser';
        $_GET['from'] = 'localhost';
        $_GET['recv'] = 'localhost';
        $_GET['user'] = 'testuser';
        $_GET['sub'] = 'testsub';
        $_GET['target'] = 'final.php';
        $_GET['port'] = 80;

        // Call the remUserFromContract method
        $route->remUserFromContract();
        echo "User removed successfully.<br>";
    } catch (Exception $e) {
        echo "Error removing user: " . $e->getMessage();
    }
}

// Add a user
addUser($route);

// Remove the user
// removeUser($route);
?>
