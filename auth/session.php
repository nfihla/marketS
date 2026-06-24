<?php

require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

$user = currentUser();

echo json_encode([
    'logged_in' => isLoggedIn(),
    'user'      => $user,
]);
