<?php
require_once __DIR__ . '/includes/auth.php';

requireGuest();

$errors = [];
$fullName = '';
$email = '';
$role = 'buyer';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName        = trim($_POST['full_name'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $role            = $_POST['role'] ?? 'buyer';

    if (!in_array($role, ['buyer', 'seller'], true)) {
        $role = 'buyer';
    }

    $errors = validateRegistrationInput($fullName, $email, $password, $confirmPassword);

    if (empty($errors)) {
        try {
            if (findUserByEmail($email)) {
                $errors[] = 'An account with this email already exists.';
            } else {
                $user = createUser($fullName, $email, $password, $role);
                loginUser($user);
                header('Location: index.html');
                exit;
            }
        } catch (PDOException $e) {
            if ((int) $e->errorInfo[1] === 1062) {
                $errors[] = 'An account with this email already exists.';
            } else {
                $errors[] = 'Database connection failed. Make sure MySQL is running and you have imported database/setup.sql.';
            }
        }
    }
}

$pageTitle = 'Register';
require __DIR__ . '/includes/auth_layout_top.php';
?>

<form method="POST" action="register.php" class="space-y-5">
    <div>
        <label for="full_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Full name</label>
        <input
            type="text"
            id="full_name"
            name="full_name"
            value="<?= htmlspecialchars($fullName) ?>"
            required
            autocomplete="name"
            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-siyandaOrange focus:border-transparent"
            placeholder="Your full name"
        >
    </div>

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
        <label for="role" class="block text-sm font-semibold text-slate-700 mb-1.5">Account type</label>
        <select
            id="role"
            name="role"
            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-siyandaOrange focus:border-transparent bg-white"
        >
            <option value="buyer" <?= $role === 'buyer' ? 'selected' : '' ?>>Buyer — browse and purchase</option>
            <option value="seller" <?= $role === 'seller' ? 'selected' : '' ?>>Seller — list products</option>
        </select>
    </div>

    <div>
        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="new-password"
            minlength="8"
            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-siyandaOrange focus:border-transparent"
            placeholder="At least 8 characters"
        >
    </div>

    <div>
        <label for="confirm_password" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm password</label>
        <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            required
            autocomplete="new-password"
            minlength="8"
            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-siyandaOrange focus:border-transparent"
            placeholder="Repeat your password"
        >
    </div>

    <button
        type="submit"
        class="w-full bg-siyandaOrange hover:bg-siyandaOrangeHover text-white font-semibold py-2.5 px-4 rounded-lg transition-colors shadow-sm"
    >
        Create account
    </button>
</form>

<p class="text-center text-sm text-slate-600 mt-6">
    Already have an account?
    <a href="login.php" class="text-siyandaOrange font-semibold hover:underline">Sign in</a>
</p>

<?php require __DIR__ . '/includes/auth_layout_bottom.php'; ?>
