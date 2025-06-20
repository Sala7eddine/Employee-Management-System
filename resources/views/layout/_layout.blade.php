<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | HR Management System</title>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('bootstrap-5.3.5-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @stack('styles')
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #10b981;
            --dark: #1e293b;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
            }

            .certificate-container {
                border: none;
                padding: 0;
            }
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
        }

        .primary-bg {
            background-color: var(--primary);
        }

        .primary-text {
            color: var(--primary);
        }

        .secondary-bg {
            background-color: var(--secondary);
        }

        .secondary-text {
            color: var(--secondary);
        }

        .dark-text {
            color: var(--dark);
        }

        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
        }

        .nav-link {
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .logout-btn {
            color: #dc3545;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="p-4">
                <h1 class="h5 fw-bold primary-text">HR Dashboard</h1>
                <div class="mt-4">
                    @auth
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle primary-bg text-white d-flex align-items-center justify-content-center"
                                style="width: 35px; height: 35px;">
                                {{ Auth::user()->role->nom == 'Administrateur' ? 'A' : 'E' }}
                            </div>
                            <div>
                                @if (auth()->user()->role->nom === 'Employé')
                                    <span class="fw-medium">
                                        {{ auth()->user()->personnel->Prenom_Nom }}
                                    </span>
                                @endif
                                @if (auth()->user()->role->nom === 'Administrateur')
                                    <span class="fw-medium">
                                        {{ auth()->user()->role->nom }}
                                    </span>
                                @endif

                                <p class="text-muted small mb-0">
                                    {{ Auth::user()->role->nom == 'Administrateur' ? 'managing manager' : Auth::user()->role->nom }}
                                </p>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            <nav class="flex-grow-1">
                <div class="px-3">
                    <div class="mb-4">
                        <h3 class="text-uppercase small fw-semibold text-muted px-2 mb-2">Management</h3>
                        <ul class="nav flex-column">
                            @auth
                                <!-- Employees Menu - Visible to all authenticated users -->
                                <li class="nav-item mb-1">
                                    <a href="{{ route('employees.index') }}"
                                        class="nav-link @if (request()->routeIs('employees.*')) active @endif">
                                        <i class="bi bi-people-fill me-2"></i> Employees
                                    </a>
                                </li>

                                <!-- Work Certificates -->
                                <li class="nav-item mb-1">
                                    <a href="{{ route('work-certificates.index') }}"
                                        class="nav-link @if (request()->routeIs('work-certificates.*')) active @endif">
                                        <i class="bi bi-file-earmark-text me-2"></i> Work Certificates
                                    </a>
                                </li>

                                <!-- Leave Requests -->
                                <li class="nav-item mb-1">
                                    <a href="{{ route('leave-requests.index') }}"
                                        class="nav-link @if (request()->routeIs('leave-requests.*')) active @endif">
                                        <i class="bi bi-clipboard-check me-2"></i> Leave Requests
                                    </a>
                                </li>

                                <!-- Salary Certificates -->
                                <li class="nav-item mb-1">
                                    <a href="{{ route('salary-certificates.index') }}"
                                        class="nav-link @if (request()->routeIs('salary-certificates.*')) active @endif">
                                        <i class="bi bi-cash-coin me-2"></i> Salary Certificates
                                    </a>
                                </li>

                                <!-- Admin Only Menu Items -->
                                @if (auth()->user()->role->nom === 'Administrateur')
                                    <li class="nav-item mb-1">
                                        <a href="{{ route('leave-decisions.index') }}"
                                            class="nav-link @if (request()->routeIs('leave-decisions.*')) active @endif">
                                            <i class="bi bi-cash-stack me-2"></i> Leave Decisions
                                        </a>
                                    </li>
                                @endif
                                {{-- 
                                <!-- HR Specific Menu Items -->
                                @if (auth()->user()->role->nom === 'RH')
                                    <li class="nav-item mb-1">
                                        <a href="{{ route('reports.hr') }}"
                                            class="nav-link @if (request()->routeIs('reports.hr')) active @endif">
                                            <i class="bi bi-graph-up me-2"></i> Netification
                                        </a>
                                    </li>
                                @endif --}}
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Logout Button in Footer -->
            <div class="sidebar-footer">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link logout-btn w-100 text-start">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
