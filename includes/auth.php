<?php

require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function currentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }

    return [
        'id'        => $_SESSION['user_id'],
        'full_name' => $_SESSION['user_name'] ?? '',
        'email'     => $_SESSION['user_email'] ?? '',
        'role'      => $_SESSION['user_role'] ?? 'buyer',
    ];
}

function requireGuest(): void
{
    if (isLoggedIn()) {
        header('Location: ../index.html');
        exit;
    }
}

function loginUser(array $user): void
{
    session_regenerate_id(true);

    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['full_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role']  = $user['role'];
}

function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function findUserByEmail(string $email): ?array
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare('SELECT id, full_name, email, password, role FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([strtolower(trim($email))]);

    $user = $stmt->fetch();
    return $user ?: null;
}

function createUser(string $fullName, string $email, string $password, string $role = 'buyer'): array
{
    $pdo = getDBConnection();

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([
        trim($fullName),
        strtolower(trim($email)),
        $hash,
        $role,
    ]);

    return [
        'id'        => (int) $pdo->lastInsertId(),
        'full_name' => trim($fullName),
        'email'     => strtolower(trim($email)),
        'role'      => $role,
    ];
}

function validateRegistrationInput(string $fullName, string $email, string $password, string $confirmPassword): array
{
    $errors = [];

    if (strlen(trim($fullName)) < 2) {
        $errors[] = 'Full name must be at least 2 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    return $errors;
}
