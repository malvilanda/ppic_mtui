<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - PPIC PT. MTU Indonesia</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0284c7',
                        secondary: '#0369a1',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-xl p-8 space-y-8">
            <!-- Logo & Header -->
            <div class="text-center space-y-2">
                <img src="<?= base_url('assets/images/logo.png') ?>" 
                     alt="MTU Logo" 
                     class="h-16 mx-auto"
                     onerror="this.outerHTML='<div class=\'bg-primary text-white text-xl font-bold py-4 px-6 rounded-lg mx-auto mb-4 text-center\'>PT. MTU Indonesia</div>'">
                
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Lupa Password</h1>
                <p class="text-gray-500 text-sm">Masukkan email Anda untuk mereset password</p>
            </div>

            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="bg-red-50 text-red-700 p-4 rounded-lg flex items-center space-x-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-lg flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <!-- Forgot Password Form -->
            <form action="<?= base_url('auth/sendResetLink') ?>" method="post" class="space-y-6">
                <?= csrf_field() ?>
                
                <!-- Email Input -->
                <div class="space-y-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-200 bg-gray-50 focus:bg-white"
                               placeholder="Email">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-primary hover:bg-secondary text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 hover:shadow-lg">
                    <i class="fas fa-paper-plane"></i>
                    <span>Kirim Link Reset</span>
                </button>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="<?= base_url('auth/login') ?>" 
                       class="text-sm text-primary hover:text-secondary transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>

            <!-- Footer -->
            <div class="pt-6 text-center text-sm text-gray-500 border-t border-gray-100">
                © <?= date('Y') ?> PT. MTU Indonesia. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html> 