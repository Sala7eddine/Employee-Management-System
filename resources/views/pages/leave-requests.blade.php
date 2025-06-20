@extends('layout._layout')

@section('content')

<body>
    
</body>
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold text-primary mb-0">
                <i class="bi bi-calendar-plus me-2"></i>Demandes de Congé
            </h2>
        </div>

        @if (session('invalideConge'))
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                {{ session('invalideConge') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('valideConge'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                {{ session('valideConge') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('leave-requests.store') }}" method="POST">
                    @csrf

                    <!-- Employee Selection -->
                    <div class="mb-4">
                        <label class="form-label small text-muted text-uppercase fw-bold">Employé</label>

                        @if (auth()->user()->role->nom === 'Administrateur')
                            <select class="form-select" name="employee_name" id="employee_select" required>
                                <option value="" disabled selected>Sélectionner un employé</option>
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
                                <option selected value="{{ auth()->user()->personnel->Matricule }}">
                                    {{ auth()->user()->personnel->Prenom_Nom }}
                                    @if (auth()->user()->personnel->Civilite)
                                        ({{ auth()->user()->personnel->Civilite }})
                                    @endif
                                </option>
                            </select>
                        @endif
                    </div>

                    <!-- Leave Type -->
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase fw-bold">Nature de congé</label>
                        <select class="form-select" name="NatureConge" id="NatureConge" required>
                            <option value="" disabled selected>Sélectionnez le type de congé</option>
                            <option value="Administratif">Administratif</option>
                            <option value="Mariage">Mariage</option>
                            <option value="Naissance">Naissance</option>
                            <option value="Exceptionnel">Exceptionnel</option>
                        </select>
                    </div>

                    <!-- Calculated Leave Days -->
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase fw-bold">Jours de congé</label>
                        <div class="p-3 bg-light rounded" id="jours_conges_display">
                            Sélectionnez des dates pour calculer
                        </div>
                        <input type="hidden" id="nombre_jours" name="nombre_jours" value="0">
                    </div>

                    <!-- Date Range -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase fw-bold">À compter du</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase fw-bold">Au</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                        </div>
                    </div>

                    <!-- Validation Message -->
                    <div id="date_validation_message" class="alert alert-danger mb-3 d-none">
                        La date de fin doit être après la date de début
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary" id="submit_button">
                            <i class="bi bi-send me-1"></i> Soumettre la demande
                        </button>
                    </div>
                </form>
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

            function calculateWorkingDays(start, end) {
                // Make copies of the dates to avoid modifying the originals
                const current = new Date(start);
                const endDate = new Date(end);

                // Swap dates if start is after end
                if (current > endDate) {
                    return 0;
                }

                let count = 0;

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

                    // Calculate total calendar days difference (inclusive)
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

                    const workingDays = calculateWorkingDays(startDate, endDate);
                    joursDisplay.textContent = workingDays + ' jour(s) ouvrable(s)';
                    nombreJoursInput.value = workingDays;

                    // Validate dates
                    if (startDate > endDate) {
                        showValidationError("La date de fin doit être après la date de début");
                        return;
                    }

                    if (workingDays > 30) {
                        showValidationError("La durée maximale de congé est de 30 jours");
                        return;
                    }

                    // If validation passes
                    clearValidationError();


                }
            }

            // Helper functions for validation messages
            function showValidationError(message) {
                validationMessage.textContent = message;
                validationMessage.classList.remove('d-none');
                joursDisplay.textContent = 'Dates invalides';
                joursDisplay.classList.add('bg-danger-subtle', 'text-danger');
                nombreJoursInput.value = '0';
                submitButton.disabled = true;
            }

            function clearValidationError() {
                validationMessage.classList.add('d-none');
                joursDisplay.classList.remove('bg-danger-subtle', 'text-danger');
                submitButton.disabled = false;
            }

            // Event listeners
            dateDebut.addEventListener('change', updateLeaveDays);
            dateFin.addEventListener('change', updateLeaveDays);

            // Initial calculation if dates are pre-filled
            updateLeaveDays();
        });


        // Initialize dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            // Enable all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>
@endsection
