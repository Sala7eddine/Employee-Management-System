<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - HR Dashboard</title>
    <link href="{{ asset('bootstrap-5.3.5-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome (for eye icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .login-card {
            width: 400px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }
        .form-control {
            border-radius: 10px;
            padding-right: 40px; /* Space for eye icon */
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            border-radius: 10px;
        }
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            z-index: 10;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            z-index: 10;
            opacity: 0; /* Start hidden */
            transition: opacity 0.3s ease;
        }
        
        .password-wrapper.active .password-toggle {
            opacity: 1; /* Show when active */
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card login-card p-4">
        <div class="text-center mb-4">
            <i class="bi bi-person-circle fs-1 text-primary"></i>
            <h4 class="mt-2">HR Dashboard Login</h4>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('to-login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="username" required>            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control" 
                        placeholder="password" 
                        required
                        oninput="toggleEyeIcon()"
                        value="{{ old('password') }}"
                    >
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleEyeIcon() {
            const passwordInput = document.getElementById('password');
            const wrapper = passwordInput.closest('.password-wrapper');
            
            // Toggle 'active' class based on input value
            if (passwordInput.value.length > 0) {
                wrapper.classList.add('active');
            } else {
                wrapper.classList.remove('active');
            }
        }

        // Initialize check on page load in case of autofill
        document.addEventListener('DOMContentLoaded', function() {
            toggleEyeIcon();
        });

        // Your existing toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
</body>
</html>