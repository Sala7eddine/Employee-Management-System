@extends('layout._layout')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold text-primary mb-0">
                <i class="bi bi-calendar3 me-2"></i>Gestion des Demandes de Congé
            </h2>
        </div>

        <!-- Filters Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="get" action="{{ route('leave-decisions.index') }}" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-5">
                        <label class="form-label small text-muted text-uppercase fw-bold">Employé</label>
                        <select class="form-select" id="employee_filter" name="matricule">
                            <option value="">Tous les employés</option>
                            @foreach ($personnel as $employee)
                                <option value="{{ $employee->Matricule }}"
                                    {{ request('matricule') == $employee->Matricule ? 'selected' : '' }}>
                                    {{ $employee->Prenom_Nom }} ({{ $employee->Matricule }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small text-muted text-uppercase fw-bold">Année</label>
                        <select class="form-select" id="year_filter" name="year">
                            @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Appliquer
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

        <!-- Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive position-relative">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-bold text-muted">Employé</th>
                                <th class="text-uppercase small fw-bold text-muted">Type</th>
                                <th class="text-uppercase small fw-bold text-muted">Date Début</th>
                                <th class="text-uppercase small fw-bold text-muted">Date Fin</th>
                                <th class="text-uppercase small fw-bold text-muted">Jours</th>
                                <th class="text-uppercase small fw-bold text-muted">status</th>
                                <th class="text-uppercase small fw-bold text-muted text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($conges as $conge)
                                <tr data-id="{{ $conge->date_Demande }}"
                                    data-type="{{ optional($conge->conge)->num_cong }}" data-date-d="{{ $conge->date_D }}"
                                    data-date-f="{{ $conge->date_F }}">
                                    <td>{{ optional($conge->personnel)->Prenom_Nom ?? 'N/A' }}</td>
                                    <td>{{ optional($conge->conge)->type_cong ?? 'N/A' }}</td>
                                    <td>{{ $conge->date_D ? \Carbon\Carbon::parse($conge->date_D)->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td>{{ $conge->date_F ? \Carbon\Carbon::parse($conge->date_F)->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        @if ($conge->date_D && $conge->date_F)
                                            @php
                                                $start = \Carbon\Carbon::parse($conge->date_D);
                                                $end = \Carbon\Carbon::parse($conge->date_F);
                                                $totalDays = 0;

                                                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                                                    if (!$date->isWeekend()) {
                                                        $totalDays++;
                                                    }
                                                }
                                            @endphp
                                            {{ $totalDays }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if ($conge->status == 'en attente')
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <i class="bi bi-check-circle me-1"></i>{{ $conge->status }}
                                            </span>
                                        @elseif ($conge->status == 'invalide')
                                            <span class="badge bg-secondary-subtle text-danger">
                                                <i class="bi bi-dash-circle me-1"></i>{{ $conge->status }}
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="bi bi-dash-circle me-1"></i>{{ $conge->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end position-relative">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                style="min-width: 200px; z-index: 1060;">
                                                <li>
                                                    <form action="{{ route('leave-requests.edit') }}" method="get"
                                                        class="d-block">
                                                        @csrf
                                                        <input type="hidden" value="{{ $conge->date_Demande }}"
                                                            name="update">
                                                        <button type="submit"
                                                            class="dropdown-item d-flex align-items-center py-2">
                                                            <i class="bi bi-pencil me-2 text-primary"></i>
                                                            <span>Modifier</span>
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item d-flex align-items-center py-2 text-danger"
                                                        onclick="showDeleteModal('{{ $conge->date_Demande }}', '{{ optional($conge->personnel)->Prenom_Nom }}')">
                                                        <i class="bi bi-trash me-2"></i>
                                                        <span>Supprimer</span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="{{ route('leave-requests.store2') }}" method="POST"
                                                        class="d-block">
                                                        @csrf
                                                        <input type="hidden" name="PDF"
                                                            value="{{ $conge->date_Demande }}">
                                                        <button type="submit"
                                                            class="dropdown-item d-flex align-items-center py-2">
                                                            <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                                                            <span>PDF</span>
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('leave-decisions.store') }}" method="post"
                                                        class="d-block">
                                                        @csrf
                                                        <input type="hidden" name="Décision"
                                                            value="{{ $conge->date_Demande }}">
                                                        <button type="submit"
                                                            class="dropdown-item d-flex align-items-center py-2">
                                                            <i class="bi bi-file-earmark-text me-2 text-info"></i>
                                                            <span>Décision</span>
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('leave-decisions.store') }}" method="post"
                                                    class="d-block">
                                                    @csrf
                                                    <input type="hidden" name="valide"
                                                        value="{{ $conge->date_Demande }}">
                                                        <input type="hidden" name="Décision"
                                                            value="{{ $conge->date_Demande }}">

                                                    <button type="submit"
                                                        class="dropdown-item d-flex align-items-center py-2 text-secondary">
                                                        <i class="bi bi-check-circle me-1 text-secondary"></i>
                                                        <span>valide</span>
                                                    </button>
                                                </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Aucune demande de congé trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $conges->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="deleteModalText">Êtes-vous sûr de vouloir supprimer cette demande de congé ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <form id="deleteForm" method="POST" action="{{ route('leave-requests.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="date_Demande" id="modalDateDemande" value="">
                            <button type="submit" class="btn btn-danger">Confirmer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('download'))
        <script>
            window.location.reload();
        </script>
    @endif

    <script>
        // Delete Modal Functions
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

        document.querySelectorAll('.dropdown').forEach(dropdown => {
            dropdown.addEventListener('show.bs.dropdown', function() {
                document.querySelector('.table-responsive').style.overflow = 'visible';
            });
            dropdown.addEventListener('hide.bs.dropdown', function() {
                document.querySelector('.table-responsive').style.overflow = 'auto';
            });
        });
    </script>

    <style>
        .table-responsive {
            overflow-x: auto;
            overflow-y: visible !important;
        }

        .dropdown-menu {
            position: absolute !important;
            right: 0 !important;
            left: auto !important;
        }
    </style>
@endsection
