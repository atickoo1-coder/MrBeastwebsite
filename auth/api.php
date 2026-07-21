<?php
/**
 * Auth API endpoint
 * Handles registration, login, logout, session check
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../includes/frontend-config.php';
require_once __DIR__ . '/../includes/auth.php';

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

$userModel = new User();

switch ($action) {

    case 'register':
        if ($method !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $errors = [];
        if (empty($input['first_name'])) $errors[] = 'First name is required.';
        if (empty($input['last_name'])) $errors[] = 'Last name is required.';
        if (empty($input['email'])) $errors[] = 'Email is required.';
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
        if (empty($input['password'])) $errors[] = 'Password is required.';
        if (strlen($input['password'] ?? '') < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($input['password'] !== ($input['confirm_password'] ?? '')) $errors[] = 'Passwords do not match.';

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
            exit;
        }

        if ($userModel->emailExists($input['email'])) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'An account with this email already exists.']);
            exit;
        }

        $userId = $userModel->create([
            'first_name' => htmlspecialchars(trim($input['first_name'])),
            'last_name'  => htmlspecialchars(trim($input['last_name'])),
            'email'      => filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL),
            'password'   => password_hash($input['password'], PASSWORD_DEFAULT),
            'phone'      => htmlspecialchars(trim($input['phone'] ?? '')),
        ]);

        Session::set('user_id', $userId);
        Session::set('user_first_name', htmlspecialchars(trim($input['first_name'])));
        Session::set('user_last_name', htmlspecialchars(trim($input['last_name'])));
        Session::set('user_email', filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL));
        Session::set('user_logged_in', true);

        echo json_encode(['success' => true, 'message' => 'Account created successfully.']);
        break;

    case 'login':
        if ($method !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        if (empty($input['email']) || empty($input['password'])) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
            exit;
        }

        $user = $userModel->findByEmail(trim($input['email']));
        if (!$user || !$userModel->verifyPassword($input['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
            exit;
        }

        session_regenerate_id(true);

        Session::set('user_id', $user['id']);
        Session::set('user_first_name', $user['first_name']);
        Session::set('user_last_name', $user['last_name']);
        Session::set('user_email', $user['email']);
        Session::set('user_logged_in', true);

        echo json_encode(['success' => true, 'message' => 'Login successful.', 'user' => [
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
        ]]);
        break;

    case 'logout':
        Session::destroy();
        echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
        break;

    case 'me':
    case 'check':
        echo json_encode([
            'success'   => true,
            'logged_in' => isUserLoggedIn(),
            'user'      => getCurrentUser(),
        ]);
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Unknown action.']);
}
