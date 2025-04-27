<?php
session_start();
require_once 'config/database.php';
require_once 'controllers/UserController.php';

$userController = new UserController($conn);

// Simple routing
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'add':
        $userController->add();
        break;
    case 'change-password':
        $userController->changePassword();
        break;
    case 'login':
        $userController->login();
        break;
    default:
        $userController->index();
        break;
} 