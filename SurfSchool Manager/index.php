<?php
// Start session
session_start();

// Load configuration and controllers
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/Controllers/StudentController.php';


// Initialize controllers
$studentController = new StudentController();


// Get action from URL
$action = isset($_GET['action']) ? $_GET['action'] : 'show-login';

// Route to appropriate controller method
switch ($action) {
    // ===== STUDENT ROUTES =====
    case 'agenda':
    case 'my-lessons':
        $studentController->showAgenda();
        break;

    case 'show-login':
        $studentController->showLoginForm();
        break;

    case 'login':
        $studentController->login();
        break;

    case 'logout':
        $studentController->logout();
        break;

    case 'show-register':
        $studentController->showRegisterForm();
        break;

    case 'register':
        $studentController->register();
        break;

    case 'level':
        // Show student's current level
        if (isset($_SESSION['student_id'])) {
            // You can add a showLevel method or handle directly
            include __DIR__ . '/Views/student/level.php';
        } else {
            header('Location: index.php?action=show-login');
        }
        break;

    // Add more routes as needed
    default:
        header('Location: index.php?action=show-login');
        exit();
}
?>