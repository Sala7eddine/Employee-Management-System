@extends('Layout._layout')

@section('title', 'Edit Leave Request')

@section('content')
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col">
                <h2 class="h4 fw-bold text-primary">
                    <i class="bi bi-calendar-check me-2"></i>Edit Leave Request
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('leave-decisions.index') }}">Leave Requests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('leave-requests.update', $leaveRequest->date_Demande) }}" method="POST">                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Employee & Leave Type</h5>
                                    
                                    <div class="mb-3">
                                        <label for="employee_select" class="form-label">Employee</label>
                                        <select class="form-select" name="Matricule" id="employee_select" required>
                                            <option value="" disabled>Select an employee</option>
                                            @foreach ($personnel as $employee)
                                                <option value="{{ $employee->Matricule }}"
                                                    {{ $leaveRequest->Matricule == $employee->Matricule ? 'selected' : '' }}>
                                                    {{ $employee->Prenom_Nom }}
                                                    @if ($employee->Civilite)
                                                        ({{ $employee->Civilite }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Matricule')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="NatureConge" class="form-label">Leave Type</label>
                                        <select class="form-select" name="num_cong" id="NatureConge" required>
                                            <option value="" disabled>Select leave type</option>
                                            @php
                                                $leaveTypes = [
                                                    'Administratif' => 'Administratif',
                                                    'Mariage' => 'Mariage', 
                                                    'Naissance' => 'Naissance',
                                                    'Exceptionnel' => 'Exceptionnel'
                                                ];
                                                
                                                $currentType = $leaveRequest->conge->type_cong ?? null;
                                            @endphp
                                            
                                            @foreach($leaveTypes as $key => $type)
                                                <option value="{{ $key }}"
                                                    {{ $currentType == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('num_cong')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="mb-3">Leave Period</h5>
                                    
                                    <div class="mb-3">
                                        <label for="date_debut" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="date_debut" name="date_D" 
                                               value="{{ $leaveRequest->date_D }}" required>
                                        @error('date_D')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="date_fin" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="date_fin" name="date_F" 
                                               value="{{ $leaveRequest->date_F }}" required>
                                        <input type="hidden" name="type_cong" value="{{ $leaveRequest->conge->type_cong }}">
                                        @error('date_F')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div id="date_validation_message" class="alert alert-danger py-2" style="display: none;">
                                        End date must be after start date
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Leave Days (excluding weekends)</label>
                                        <div id="jours_conges_display" class="form-control bg-light">
                                            @php
                                                $start = \Carbon\Carbon::parse($leaveRequest->date_D);
                                                $end = \Carbon\Carbon::parse($leaveRequest->date_F);
                                                $workingDays = 0;
                                
                                                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                                                    if (!$date->isWeekend()) {
                                                        $workingDays++;
                                                    }
                                                }
                                            @endphp
                                            {{ $workingDays }} day(s)
                                        </div>
                                        <input type="hidden" id="nombre_jours" name="nombre_jours" value="{{ $workingDays }}">
                                        <!-- Ensure the date_Demande is included as a hidden field for the controller's validation -->
                                        <input type="hidden" name="date_Demande" value="{{ $leaveRequest->date_Demande }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2" id="submit_button">
                                    <i class="bi bi-save me-1"></i> Update
                                </button>
                                <a href="{{ route('leave-decisions.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateDebut = document.getElementById('date_debut');
            const dateFin = document.getElementById('date_fin');
            const joursDisplay = document.getElementById('jours_conges_display');
            const nombreJoursInput = document.getElementById('nombre_jours');
            const validationMessage = document.getElementById('date_validation_message');
            const submitButton = document.getElementById('submit_button');
    
            function calculateWorkingDays(start, end) {
                let count = 0;
                const current = new Date(start);
                const endDate = new Date(end);
    
                while (current <= endDate) {
                    const day = current.getDay();
                    if (day !== 0 && day !== 6) { // Skip Sunday (0) and Saturday (6)
                        count++;
                    }
                    current.setDate(current.getDate() + 1);
                }
    
                return count;
            }
    
            function updateLeaveDays() {
                if (dateDebut.value && dateFin.value) {
                    const startDate = new Date(dateDebut.value);
                    const endDate = new Date(dateFin.value);
    
                    if (startDate > endDate) {
                        validationMessage.style.display = 'block';
                        joursDisplay.textContent = 'Invalid dates';
                        nombreJoursInput.value = '0';
                        submitButton.disabled = true;
                        return;
                    }
    
                    validationMessage.style.display = 'none';
                    submitButton.disabled = false;
    
                    const workingDays = calculateWorkingDays(startDate, endDate);
                    joursDisplay.textContent = workingDays + ' day(s)';
                    nombreJoursInput.value = workingDays;
                }
            }
    
            dateDebut.addEventListener('change', updateLeaveDays);
            dateFin.addEventListener('change', updateLeaveDays);
    
            // Initial calculation if dates are pre-filled
            updateLeaveDays();
        });
    </script>
@endsection