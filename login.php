<?php
require_once __DIR__ . '/includes/auth.php';

requireGuest();

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } else {
        try {
            $user = findUserByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                $errors[] = 'Invalid email or password.';
            } else {
                loginUser($user);
                header('Location: index.html');
                exit;
            }
        } catch (PDOException $e) {
            $errors[] = 'Database connection failed. Make sure MySQL is running and you have imported database/setup.sql.';
        }
    }
}

$pageTitle = 'Login';
require __DIR__ . '/includes/auth_layout_top.php';
?>

<form method="POST" action="login.php" class="space-y-5">
    <div>
        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email address</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($email) ?>"
            required
            autocomplete="email"
            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-siyandaOrange focus:border-transparent"
            placeholder="you@example.com"
        >
    </div>

    <div>
        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="current-password"
            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-siyandaOrange focus:border-transparent"
            placeholder="Enter your password"
        >
    </div>

    <button
        type="submit"
        class="w-full bg-siyandaOrange hover:bg-siyandaOrangeHover text-white font-semibold py-2.5 px-4 rounded-lg transition-colors shadow-sm"
    >
        Sign in
    </button>
</form>

<p class="text-center text-sm text-slate-600 mt-6">
    Don't have an account?
    <a href="register.php" class="text-siyandaOrange font-semibold hover:underline">Create one</a>
</p>

<?php require __DIR__ . '/includes/auth_layout_bottom.php'; ?>
