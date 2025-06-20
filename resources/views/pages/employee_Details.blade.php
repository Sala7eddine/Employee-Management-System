@extends('Layout._layout')

@section('title', 'Employee Details')

@section('content')
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col">
                <h2 class="h4 fw-bold text-primary">
                    <i class="bi bi-person-lines-fill me-2"></i>Employee Details
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $employee->Prenom_Nom }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        @if ($employee->Photo)
                            <img src="{{ asset('storage/' . $employee->Photo) }}" 
                                class="rounded-circle mb-3" 
                                alt="{{ $employee->Prenom_Nom }}"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->Prenom_Nom) }}&background=random" 
                                class="rounded-circle mb-3" 
                                alt="{{ $employee->Prenom_Nom }}"
                                style="width: 150px; height: 150px;">
                        @endif
                        <h4 class="mb-2">{{ $employee->Prenom_Nom }}</h4>
                        <p class="text-muted mb-3">{{ $employee->Fonction }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <button class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="small text-muted mb-1">Employee ID</p>
                            <p class="mb-0">{{ $employee->Matricule }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="small text-muted mb-1">CIN</p>
                            <p class="mb-0">{{ $employee->CIN }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="small text-muted mb-1">Date of Birth</p>
                            <p class="mb-0">{{ $employee->Date_Naissance }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="small text-muted mb-1">Gender</p>
                            <p class="mb-0">{{ $employee->Sexe }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="small text-muted mb-1">Phone</p>
                            <p class="mb-0">{{ $employee->Telephone }}</p>
                        </div>
                        <div>
                            <p class="small text-muted mb-1">Email</p>
                            <p class="mb-0">{{ $employee->Email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Employment Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="small text-muted mb-1">Department</p>
                                <p class="mb-0">{{ $employee->Specialite_Origine }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="small text-muted mb-1">Position</p>
                                <p class="mb-0">{{ $employee->Fonction }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="small text-muted mb-1">Date Hired</p>
                                <p class="mb-0">{{ $employee->Date_Recrutement }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="small text-muted mb-1">Status</p>
                                <p class="mb-0">
                                    @if ($employee->Etat == 'Actif')
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-check-circle me-1"></i>{{ $employee->Etat }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            <i class="bi bi-dash-circle me-1"></i>{{ $employee->Etat }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Documents</h5>
                            <button class="btn btn-sm btn-primary">
                                <i class="bi bi-plus me-1"></i> Add Document
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th>Type</th>
                                        <th>Upload Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample document rows -->
                                    <tr>
                                        <td>Employment Contract.pdf</td>
                                        <td>Contract</td>
                                        <td>{{ $employee->Date_Recrutement }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ID_Copy.jpg</td>
                                        <td>Identification</td>
                                        <td>{{ $employee->Date_Recrutement }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection