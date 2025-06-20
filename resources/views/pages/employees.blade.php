@extends('Layout._layout')

@section('title', 'Employees')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header with Animation -->
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
            <h2 class="h4 fw-bold text-primary mb-0">
                <i class="bi bi-people-fill me-2"></i>Employee Management
            </h2>
            @if (auth()->user()->role->nom === 'Administrateur')
                <a href="{{ route('employees.create') }}" class="btn btn-primary text-white shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add Employee
                </a>
            @endif
        </div>

        <!-- Stats Cards with Hover Effects -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Employees</p>
                                <h3 class="h4 fw-bold text-dark mb-0">{{ $totalEmployees }}</h3>
                                <p class="small text-success mb-0 mt-2"><i
                                        class="bi bi-arrow-up me-1"></i>{{ $employeeGrowth }}% from last month</p>
                            </div>
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                style="width: 54px; height: 54px;">
                                <i class="bi bi-people-fill text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">New Hires</p>
                                <h3 class="h4 fw-bold text-dark mb-0">{{ $newHires }}</h3>
                                <p class="small text-success mb-0 mt-2"><i
                                        class="bi bi-arrow-up me-1"></i>{{ $hireGrowth }}% from last month</p>
                            </div>
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                                style="width: 54px; height: 54px;">
                                <i class="bi bi-person-plus-fill text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Open Positions</p>
                                {{-- <h3 class="h4 fw-bold text-dark mb-0">{{ $openPositions }}</h3> --}}
                                <h3 class="h4 fw-bold text-dark mb-0">{{ 2 }}</h3>
                                <p class="small text-danger mb-0 mt-2"><i
                                        class="bi bi-arrow-down me-1"></i>{{ $positionChange }}% from last month</p>
                            </div>
                            <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center"
                                style="width: 54px; height: 54px;">
                                <i class="bi bi-briefcase-fill text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">On Leave</p>
                                <h3 class="h4 fw-bold text-dark mb-0">{{ $onLeave }}</h3>
                                <p class="small text-muted mb-0 mt-2"><i
                                        class="bi bi-dash me-1"></i>{{ $leaveChange == 0 ? 'No change' : ($leaveChange > 0 ? '+' . $leaveChange . '%' : $leaveChange . '%') }}
                                </p>
                            </div>
                            <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center"
                                style="width: 54px; height: 54px;">
                                <i class="bi bi-calendar2-event-fill text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Controls -->
        <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeIn" style="animation-delay: 0.5s">
            <div class="card-body">
                <form action="{{ route('employees.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Search employees..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="department" class="form-select">
                            <option value="">All Departments</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}"
                                    {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="Actif" {{ request('status') == 'Actif' ? 'selected' : '' }}>Active</option>
                            <option value="Inactif" {{ request('status') == 'Inactif' ? 'selected' : '' }}>Inactive
                            </option>
                            <option value="Retraité" {{ request('status') == 'Retraité' ? 'selected' : '' }}>Retired
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel-fill me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success_sup'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                {{ session('success_sup') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <!-- Employee Table with Improved Styling -->
        <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeIn" style="animation-delay: 0.6s">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary fw-semibold">Employee Directory</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-1"></i> Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-download me-2"></i>Export to
                                    Excel</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print List</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-envelope me-2"></i>Email
                                    Selected</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-bold text-muted ps-4">ID</th>
                                <th class="text-uppercase small fw-bold text-muted">Employee</th>
                                <th class="text-uppercase small fw-bold text-muted">Position</th>
                                <th class="text-uppercase small fw-bold text-muted">Department</th>
                                <th class="text-uppercase small fw-bold text-muted">Status</th>
                                <th class="text-uppercase small fw-bold text-muted text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (auth()->user()->role->nom === 'Administrateur')
                                @forelse ($employees as $employee)
                                    <tr class="employee-row">
                                        <td class="ps-4">{{ $employee->Matricule }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($employee->Photo)
                                                    <div class="avatar-wrapper me-3">
                                                        <img src="{{ asset('storage/' . $employee->Photo) }}"
                                                            class="rounded-circle employee-avatar"
                                                            alt="{{ $employee->Prenom_Nom }}">
                                                    </div>
                                                @else
                                                    <div class="avatar-wrapper me-3">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->Prenom_Nom) }}&background=random"
                                                            class="rounded-circle employee-avatar"
                                                            alt="{{ $employee->Prenom_Nom }}">
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $employee->Prenom_Nom }}</div>
                                                    <div class="small text-muted">{{ $employee->CIN }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $employee->Fonction }}</td>
                                        <td>
                                            <span class="department-badge">{{ $employee->Specialite_Origine }}</span>
                                        </td>
                                        <td>
                                            @if ($employee->Etat == 'Actif')
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="bi bi-check-circle me-1"></i>{{ $employee->Etat }}
                                                </span>
                                            @elseif ($employee->Etat == 'Retraité')
                                                <span class="badge bg-secondary-subtle text-danger">
                                                    <i class="bi bi-dash-circle me-1"></i>{{ $employee->Etat }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    <i class="bi bi-dash-circle me-1"></i>{{ $employee->Etat }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2" style="margin-top: 15px">
                                                <form action="{{ route('employees.details', $employee->Matricule) }}"
                                                    method="get">
                                                    @csrf
                                                    <input type="hidden" value="{{ $employee->Matricule }}"
                                                        name="Matricule">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary "
                                                        data-bs-toggle="tooltip" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('employees.edit', $employee->Matricule) }}" method="GET">
                                                    <input type="hidden" value="{{ $employee->Matricule }}"
                                                        name="Matricule">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="tooltip" title="edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </form>
                                                <form action="">
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $employee->Matricule }}"
                                                        title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                <!-- Delete Confirmation Modal -->
                                                <div class="modal fade" id="deleteModal{{ $employee->Matricule }}"
                                                    tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirm Delete</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete
                                                                    {{ $employee->Prenom_Nom }}?
                                                                </p>
                                                                <p class="text-danger small"><i
                                                                        class="bi bi-exclamation-triangle me-1"></i>This
                                                                    action
                                                                    cannot be undone.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('employees.destroy', $employee) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete
                                                                        Employee</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No employees found matching your
                                            criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            @endif

                            @if (auth()->user()->role->nom === 'Employé')
                                <tr class="employee-row">
                                    <td class="ps-4">{{ auth()->user()->personnel->Matricule }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if (auth()->user()->personnel->Photo)
                                            <div class="avatar-wrapper me-3">
                                                <img src="{{ asset('storage/' . auth()->user()->personnel->Photo) }}"
                                                    class="rounded-circle employee-avatar"
                                                    alt="{{ auth()->user()->personnel->Prenom_Nom }}">
                                            </div>
                                        @else
                                            <div class="avatar-wrapper me-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->personnel->Prenom_Nom) }}&background=random"
                                                    class="rounded-circle employee-avatar"
                                                    alt="{{ auth()->user()->personnel->Prenom_Nom }}">
                                            </div>
                                        @endif
                                            <div>
                                                <div class="fw-semibold">{{ auth()->user()->personnel->Prenom_Nom }}</div>
                                                <div class="small text-muted">{{ auth()->user()->personnel->CIN }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ auth()->user()->personnel->Fonction }}</td>
                                    <td>
                                        <span
                                            class="department-badge">{{ auth()->user()->personnel->Specialite_Origine }}</span>
                                    </td>
                                    <td>
                                        @if (auth()->user()->personnel->Etat == 'Actif')
                                            <span class="badge bg-success-subtle text-success">
                                                <i
                                                    class="bi bi-check-circle me-1"></i>{{ auth()->user()->personnel->Etat }}
                                            </span>
                                        @elseif (auth()->user()->personnel->Etat == 'Retraité')
                                            <span class="badge bg-secondary-subtle text-danger">
                                                <i
                                                    class="bi bi-dash-circle me-1"></i>{{ auth()->user()->personnel->Etat }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <i
                                                    class="bi bi-dash-circle me-1"></i>{{ auth()->user()->personnel->Etat }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2" style="margin-top: 15px">
                                            <form
                                                action="{{ route('employees.details', auth()->user()->personnel->Matricule) }}"
                                                method="get">
                                                <input type="hidden" value="{{ auth()->user()->personnel->Matricule }}"
                                                    name="Matricule">
                                                <button type="submit" class="btn btn-sm btn-outline-primary "
                                                    data-bs-toggle="tooltip" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('employees.edit',auth()->user()->personnel->Matricule) }}" method="GET">
                                                <input type="hidden" value="{{ auth()->user()->personnel->Matricule }}"
                                                name="Matricule">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                    title="edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </form>

                                            <!-- Delete Confirmation Modal -->
                                            <div class="modal fade" id="deleteModal{{ auth()->user()->personnel->id }}"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirm Delete</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete
                                                                {{ auth()->user()->personnel->Prenom_Nom }}?
                                                            </p>
                                                            <p class="text-danger small"><i
                                                                    class="bi bi-exclamation-triangle me-1"></i>This action
                                                                cannot be undone.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <form
                                                                action="{{ route('employees.destroy', auth()->user()->personnel) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Delete
                                                                    Employee</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @if (auth()->user()->role->nom === 'Administrateur')
                <div class="card-footer-Pagination bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-muted small mb-0">Showing <span
                                class="fw-semibold">{{ $employees->firstItem() }}-{{ $employees->lastItem() }}</span> of
                            <span class="fw-semibold">{{ $employees->total() }}</span> employees
                        </p>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $employees->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Charts Row with Enhanced Appearance -->

        <div class="row g-4 mb-4">
            <div class="col-lg-6 animate__animated animate__fadeIn" style="animation-delay: 0.7s">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-semibold text-primary mb-0">Department Distribution</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" id="exportChartPng"><i
                                                class="bi bi-download me-2"></i>Export as PNG</a></li>
                                    <li><a class="dropdown-item" href="" id="refreshChart"><i
                                                class="bi bi-arrow-clockwise me-2"></i>Refresh</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="height: 250px;">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 animate__animated animate__fadeIn" style="animation-delay: 0.8s">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-semibold text-primary mb-0">Hiring Trends</h5>
                            {{-- <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary active">Monthly</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Quarterly</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Yearly</button>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <div class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="height: 250px;">
                            <div class="text-center">
                                <i class="bi bi-graph-up-arrow text-primary fs-1 mb-3"></i>
                                <p class="text-muted">Line chart would go here</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between text-muted small">
                                @foreach ($hiringTrends as $month => $count)
                                    <span>{{ $month }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div> --}}

                        <div style="height: 250px;">
                            <canvas id="hiringTrendsChart"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    @endsection

    <!-- Add Chart.js -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Prepare data in JavaScript format
                const deptData = {!! json_encode([
                    'labels' => array_keys($departmentDistribution),
                    'values' => array_values($departmentDistribution),
                    'colors' => array_values($deptColors),
                    'counts' => array_values($departmentCounts),
                ]) !!};

                // Department Distribution Pie Chart
                const ctx = document.getElementById('departmentChart').getContext('2d');
                const departmentChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: deptData.labels,
                        datasets: [{
                            data: deptData.values,
                            backgroundColor: deptData.colors,
                            borderWidth: 0, // No border for cleaner look
                            counts: deptData.counts
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%', // Creates the donut effect
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    padding: 20,
                                    usePointStyle: true, // Uses circles instead of rectangles
                                    pointStyle: 'circle', // Ensures circular legend markers
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.labels.map(function(label, i) {
                                                const dataset = data.datasets[0];
                                                const value = dataset.data[i];
                                                const count = dataset.counts[i];
                                                const color = dataset.backgroundColor[i];

                                                return {
                                                    text: `${label} (${count})`, // Format: "Department (Count)"
                                                    fillStyle: color,
                                                    hidden: isNaN(dataset.data[i]) || chart
                                                        .getDatasetMeta(0).data[i].hidden,
                                                    index: i
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${context.raw}% (${context.dataset.counts[context.dataIndex]} employees)`;
                                    }
                                }
                            },
                            // Main title configuration
                            // title: {
                            //     display: true,
                            //     text: 'Department Distribution',
                            //     font: {
                            //         size: 16,
                            //         weight: 'bold'
                            //     },
                            //     padding: {
                            //         top: 10,
                            //         bottom: 10,
                            //     },

                            // },
                            // // Subtitle configuration
                            // subtitle: {
                            //     display: true,
                            //     text: `${deptData.labels.length} Specializations`,
                            //     font: {
                            //         size: 12,
                            //         weight: 'normal'
                            //     },
                            //     padding: {
                            //         bottom: 20
                            //     }
                            // }
                        },
                        // Line segment styling
                        elements: {
                            arc: {
                                borderWidth: 2,
                                borderColor: '#fff',
                                borderAlign: 'center',
                                spacing: 2 // Adds small gaps between segments
                            }
                        }
                    }
                });

                // Export as PNG functionality
                document.getElementById('exportChartPng').addEventListener('click', function(e) {
                    e.preventDefault();
                    const link = document.createElement('a');
                    link.download = 'department-distribution.png';
                    link.href = document.getElementById('departmentChart').toDataURL('image/png');
                    link.click();
                });

                // Refresh chart functionality
                document.getElementById('refreshChart').addEventListener('click', function(e) {
                    e.preventDefault();
                    departmentChart.update();
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Hiring Trends Chart
                const hiringData = {!! json_encode([
                    'months' => array_keys($hiringTrends),
                    'departments' => $departments,
                    'values' => array_values($hiringTrends),
                    'colors' => $deptColors,
                    'counts' => $departmentCounts,
                ]) !!};

                // Prepare datasets
                const hiringDatasets = hiringData.departments.map((dept, index) => ({
                    label: dept,
                    data: hiringData.values.map(monthData => monthData[dept] || 0),
                    borderColor: hiringData.colors[dept] || getRandomColor(),
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.1,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: hiringData.colors[dept] || getRandomColor(),
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }));

                // Create Hiring Trends Chart
                const hiringCtx = document.getElementById('hiringTrendsChart').getContext('2d');
                const hiringChart = new Chart(hiringCtx, {
                    type: 'line',
                    data: {
                        labels: hiringData.months,
                        datasets: hiringDatasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    padding: 20,
                                    usePointStyle: true,
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.datasets.map((dataset, i) => {
                                                const total = dataset.data.reduce((a, b) => a + b,
                                                    0);
                                                return {
                                                    text: `${dataset.label} (${total})`,
                                                    fillStyle: dataset.borderColor,
                                                    hidden: !chart.getDatasetMeta(i).visible,
                                                    index: i
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Hiring Trends',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                padding: {
                                    top: 10,
                                    bottom: 10
                                }
                            },
                            subtitle: {
                                display: true,
                                text: `${hiringData.departments.length} Departments Tracked`,
                                font: {
                                    size: 12,
                                    weight: 'normal'
                                },
                                padding: {
                                    bottom: 20
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.raw} hires`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                title: {
                                    display: true,
                                    text: 'Number of Hires'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        elements: {
                            line: {
                                borderWidth: 2
                            },
                            point: {
                                radius: 4,
                                hoverRadius: 6
                            }
                        }
                    }
                });

                // Helper function for random colors
                function getRandomColor() {
                    return '#' + Math.floor(Math.random() * 16777215).toString(16);
                }

                // Export functionality
                document.getElementById('exportHiringPng').addEventListener('click', function(e) {
                    e.preventDefault();
                    const link = document.createElement('a');
                    link.download = 'hiring-trends.png';
                    link.href = document.getElementById('hiringTrendsChart').toDataURL('image/png');
                    link.click();
                });

                // Refresh functionality
                document.getElementById('refreshHiringChart').addEventListener('click', function(e) {
                    e.preventDefault();
                    hiringChart.update();
                });

                // Time period toggle would need AJAX implementation
                document.querySelectorAll('.btn-group .btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.btn-group .btn').forEach(b => b.classList.remove(
                            'active'));
                        this.classList.add('active');
                        // AJAX call would go here to fetch new data
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            /* Custom Styles for Employee Page */
            .transition-hover {
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .transition-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
            }

            .shadow-sm {
                box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
            }

            .page-item.active .page-link {
                background-color: #4f46e5;
                border-color: #4f46e5;
            }

            .page-link:hover {
                color: #4f46e5;
            }

            .avatar-wrapper {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                overflow: hidden;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .employee-avatar {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .department-badge {
                display: inline-block;
                padding: 0.25rem 0.5rem;
                background-color: #f8f9fa;
                border-radius: 0.25rem;
                font-size: 0.875rem;
                color: #495057;
            }

            .employee-row {
                transition: background-color 0.2s;
            }

            .employee-row:hover {
                background-color: rgba(0, 123, 255, 0.04);
            }

            /* Enhanced status badges */
            .bg-success-subtle {
                background-color: rgba(25, 135, 84, 0.1) !important;
            }

            .bg-secondary-subtle {
                background-color: rgba(108, 117, 125, 0.1) !important;
            }

            /* Animate.css Animation Duration */
            .animate__animated {
                animation-duration: 0.6s;
            }

            /* Pagination styling */
            .pagination {
                margin: 0;
            }

            .page-item.active .page-link {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }

            .page-link {
                color: #0d6efd;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .card-header {
                    padding: 1rem;
                }
            }
        </style>
    @endpush

    <!-- Delete Modal Functions -->
    <script>
        function showDeleteModal(dateDemande, employeeName) {
            document.getElementById('deleteModalText').textContent =
                `Êtes-vous sûr de vouloir supprimer la demande de congé de ${employeeName} ?`;
            document.getElementById('modalDateDemande').value = dateDemande;
            var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Initialize dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            // Enable all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // For demo purposes - could be replaced with actual charts
                console.log('Employees page loaded');

                // Add animation when scrolling - optional enhancement
                const animateOnScroll = () => {
                    const elements = document.querySelectorAll('.animate__animated');
                    elements.forEach(element => {
                        const position = element.getBoundingClientRect();
                        if (position.top < window.innerHeight && position.bottom >= 0) {
                            element.style.visibility = 'visible';
                        }
                    });
                };

                // Initial check and add scroll listener
                animateOnScroll();
                window.addEventListener('scroll', animateOnScroll);
            });
        </script>
    @endpush
