<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — Siyanda Market</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        siyandaOrange: '#f39c12',
                        siyandaOrangeHover: '#d6840d',
                        siyandaDarkGrey: '#2c3e50',
                        siyandaLightGrey: '#bdc3c7',
                        siyandaAccentGreen: '#27ae60',
                        siyandaBg: '#f8f9fa'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', Arial, Helvetica, sans-serif; background-color: #f8f9fa; }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800">

    <header class="bg-siyandaDarkGrey text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="index.html" class="flex items-center gap-2">
                    <div class="bg-siyandaOrange text-white p-2 rounded-lg flex items-center justify-center font-black shadow">
                        <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight">
                        Siyanda<span class="text-siyandaOrange">Market</span>
                    </span>
                </a>
                <a href="index.html" class="text-sm text-slate-300 hover:text-siyandaOrange transition-colors">
                    &larr; Back to home
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-siyandaDarkGrey"><?= htmlspecialchars($pageTitle) ?></h1>
                    <p class="text-sm text-slate-500 mt-1">
                        <?= $pageTitle === 'Login' ? 'Welcome back to Siyanda Market' : 'Join the township entrepreneur marketplace' ?>
                    </p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc list-inside space-y-1">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($pageTitle === 'Login' && isset($_GET['logged_out'])): ?>
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        You have been logged out successfully.
                    </div>
                <?php endif; ?>

                <?php if ($pageTitle === 'Login' && isset($_GET['registered'])): ?>
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        Account created! Please sign in with your credentials.
                    </div>
                <?php endif; ?>
