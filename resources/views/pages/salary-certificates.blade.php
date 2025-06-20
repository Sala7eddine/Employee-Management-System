@extends('layout._layout')

@section('content')
<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold text-primary mb-0">
            <i class="bi bi-cash-coin me-2"></i>Attestation de Salaire
        </h2>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('salary-certificates.store') }}" method="POST">
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

                <!-- Gains Section -->
                <div class="mb-4 p-3 border rounded">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-plus-circle me-2"></i>Gains
                    </h5>
                    
                    <div class="row g-3">
                        <!-- Row 1 -->
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Salaire de base</label>
                            <input type="number" class="form-control" id="salaire_base" name="salaire_base" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Prime de résidence</label>
                            <input type="number" class="form-control" id="prime_residence" name="prime_residence" step="0.01">
                        </div>
                        
                        <!-- Row 2 -->
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Heures Supplémentaires</label>
                            <input type="number" class="form-control" id="heures_supp" name="heures_supp" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Indemnité de transport</label>
                            <input type="number" class="form-control" id="transport" name="transport" step="0.01">
                        </div>
                        
                        <!-- Row 3 -->
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Allocation familiale</label>
                            <input type="number" class="form-control" id="allocation_fam" name="allocation_fam" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Indemnité de qualification</label>
                            <input type="number" class="form-control" id="indemnite_qualif" name="indemnite_qualif" step="0.01">
                        </div>
                        
                        <!-- Row 4 -->
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Ind. Logement</label>
                            <input type="number" class="form-control" id="indemnite_logement" name="indemnite_logement" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Indemnité spéciale</label>
                            <input type="number" class="form-control" id="indemnite_speciale" name="indemnite_speciale" step="0.01">
                        </div>
                        
                        <!-- Row 5 -->
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Retraite complémentaire</label>
                            <input type="number" class="form-control" id="retraite_compl" name="retraite_compl" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">A.D.I : Tranche obligatoire</label>
                            <input type="number" class="form-control" id="adi" name="adi" step="0.01">
                        </div>
                    </div>
                    
                    <!-- Total Gains -->
                    <div class="d-flex justify-content-between align-items-center mt-3 p-2 bg-light rounded">
                        <span class="fw-bold">TOTAL GAIN</span>
                        <span class="fw-bold text-success" id="total_gain">0.00</span>
                    </div>
                </div>

                <!-- Retenues Section -->
                <div class="mb-4 p-3 border rounded">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-dash-circle me-2"></i>Retenues
                    </h5>
                    
                    <div class="row g-3">
                        <!-- Row 1 -->
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Retraite RCAR</label>
                            <input type="number" class="form-control" id="retraite_rcar" name="retraite_rcar" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Mutuelle (ATLANTA)</label>
                            <input type="number" class="form-control" id="mutuelle" name="mutuelle" step="0.01">
                        </div>
                        
                        <!-- Row 2 -->
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Assurance de vie</label>
                            <input type="number" class="form-control" id="assurance_vie" name="assurance_vie" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Prêt Aid El-Adha</label>
                            <input type="number" class="form-control" id="pret_aid" name="pret_aid" step="0.01">
                        </div>
                        
                        <!-- Row 3 -->
                        <div class="col-md-6">
                            <label class="form-label small text-muted">I.R (Impôt sur le revenu)</label>
                            <input type="number" class="form-control" id="ir" name="ir" step="0.01">
                        </div>
                    </div>
                    
                    <!-- Total Retenues -->
                    <div class="d-flex justify-content-between align-items-center mt-3 p-2 bg-light rounded">
                        <span class="fw-bold">TOTAL RETENUES</span>
                        <span class="fw-bold text-danger" id="total_retenues">0.00</span>
                    </div>
                </div>

                <!-- Net à payer -->
                <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-primary bg-opacity-10 rounded">
                    <span class="fw-bold fs-5">NET A PAYER</span>
                    <span class="fw-bold fs-4 text-primary" id="net_payer">0.00</span>
                </div>

                <!-- Submit Button -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gainInputs = document.querySelectorAll('#salaire_base, #prime_residence, #heures_supp, #transport, #allocation_fam, #indemnite_qualif, #indemnite_logement, #indemnite_speciale, #retraite_compl, #adi');
    const retenueInputs = document.querySelectorAll('#retraite_rcar, #mutuelle, #assurance_vie, #pret_aid, #ir');
    
    function calculateTotals() {
        // Calculate total gains
        let totalGain = 0;
        gainInputs.forEach(input => {
            totalGain += parseFloat(input.value) || 0;
        });
        document.getElementById('total_gain').textContent = totalGain.toFixed(2);
        
        // Calculate total retenues
        let totalRetenues = 0;
        retenueInputs.forEach(input => {
            totalRetenues += parseFloat(input.value) || 0;
        });
        document.getElementById('total_retenues').textContent = totalRetenues.toFixed(2);
        
        // Calculate net à payer
        const netPayer = totalGain - totalRetenues;
        document.getElementById('net_payer').textContent = netPayer.toFixed(2);
    }
    
    // Add event listeners to all inputs
    gainInputs.forEach(input => {
        input.addEventListener('input', calculateTotals);
    });
    
    retenueInputs.forEach(input => {
        input.addEventListener('input', calculateTotals);
    });
    
    // Initial calculation
    calculateTotals();
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gainInputs = document.querySelectorAll('#salaire_base, #prime_residence, #heures_supp, #transport, #allocation_fam, #indemnite_qualif, #indemnite_logement, #indemnite_speciale, #retraite_compl, #adi');
        const retenueInputs = document.querySelectorAll('#retraite_rcar, #mutuelle, #assurance_vie, #pret_aid, #ir');
    
        function calculateTotals() {
            let totalGain = 0;
            gainInputs.forEach(input => {
                totalGain += parseFloat(input.value) || 0;
            });
            document.getElementById('total_gain').textContent = totalGain.toFixed(2);
    
            let totalRetenues = 0;
            retenueInputs.forEach(input => {
                totalRetenues += parseFloat(input.value) || 0;
            });
            document.getElementById('total_retenues').textContent = totalRetenues.toFixed(2);
    
            const netPayer = totalGain - totalRetenues;
            document.getElementById('net_payer').textContent = netPayer.toFixed(2);
        }
    
        function fetchEmployeeSalary(matricule) {
            fetch(`/personnel/${matricule}/salaire`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('salaire_base').value = data.salaire_base;
                    document.getElementById('prime_residence').value = data.prime_residence;
                    document.getElementById('heures_supp').value = data.heures_supp;
                    document.getElementById('transport').value = data.transport;
                    document.getElementById('allocation_fam').value = data.allocation_fam;
                    document.getElementById('indemnite_qualif').value = data.indemnite_qualif;
                    document.getElementById('indemnite_logement').value = data.indemnite_logement;
                    document.getElementById('indemnite_speciale').value = data.indemnite_speciale;
                    document.getElementById('retraite_compl').value = data.retraite_compl;
                    document.getElementById('adi').value = data.adi;
    
                    document.getElementById('retraite_rcar').value = data.retraite_rcar;
                    document.getElementById('mutuelle').value = data.mutuelle;
                    document.getElementById('assurance_vie').value = data.assurance_vie;
                    document.getElementById('pret_aid').value = data.pret_aid;
                    document.getElementById('ir').value = data.ir;
    
                    calculateTotals();
                });
        }
    
        const employeeSelect = document.getElementById('employee_select');
        if (employeeSelect) {
            employeeSelect.addEventListener('change', function () {
                const matricule = this.value;
                if (matricule) {
                    fetchEmployeeSalary(matricule);
                }
            });
    
            // Trigger auto-fetch for current user (if only one option)
            if (employeeSelect.options.length === 1) {
                fetchEmployeeSalary(employeeSelect.value);
            }
        }
    
        // Initial calculation (in case fields are prefilled)
        calculateTotals();
    });
    </script>
    
@endsection