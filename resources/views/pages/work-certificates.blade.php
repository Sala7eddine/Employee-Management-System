@extends('layout._layout')

@section('content')
<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold text-primary mb-0">
            <i class="bi bi-file-earmark-text me-2"></i>Work Certificate
        </h2>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('work-certificates.store') }}" method="POST">
                @csrf
                
                <!-- Employee Selection -->
                <div class="mb-4">
                    <label class="form-label small text-muted text-uppercase fw-bold">Select Employee</label>

                    @if (auth()->user()->role->nom === 'Administrateur')

                    <select class="form-select" name="employee_name" id="employee_select" required>
                        <option value="" disabled selected>Choose an employee...</option>
                        @foreach ($personnel as $employee)
                            <option value="{{ $employee->Matricule }}">
                                {{ $employee->Prenom_Nom }}
                                @if ($employee->Civilite)
                                    ({{ $employee->Civilite }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @endif

                    @if (auth()->user()->role->nom === 'Employé')
                    <select class="form-select" name="employee_name" id="employee_select" required>
                            <option value="{{ auth()->user()->personnel->Matricule }}">
                                {{ auth()->user()->personnel->Prenom_Nom }}
                                @if (auth()->user()->personnel->Civilite)
                                    ({{ auth()->user()->personnel->Civilite }})
                                @endif
                            </option>
                    </select>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-download me-1"></i> Generate Certificate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection