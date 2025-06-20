@extends('Layout._layout')

@section('title', isset($employee) ? 'Edit Employee' : 'Add Employee')

@section('content')
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col">
                <h2 class="h4 fw-bold text-primary">
                    <i class="bi bi-person-plus-fill me-2"></i>{{ isset($employee) ? 'Edit Employee' : 'Add New Employee' }}
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($employee) ? 'Edit' : 'Add' }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form
                            action="{{ isset($employee) ? route('employees.update', $employee->Matricule) : route('employees.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($employee))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Personal Information</h5>

                                    <div class="mb-3">
                                        <label for="Civilite" class="form-label">Title</label>
                                        <select class="form-select" id="Civilite" name="Civilite">
                                            <option value="Mr"
                                                {{ old('Civilite', $employee->Civilite ?? '') == 'Mr' ? 'selected' : '' }}>
                                                Mr</option>
                                            <option value="Mrs"
                                                {{ old('Civilite', $employee->Civilite ?? '') == 'Mrs' ? 'selected' : '' }}>
                                                Mrs</option>
                                            <option value="Ms"
                                                {{ old('Civilite', $employee->Civilite ?? '') == 'Ms' ? 'selected' : '' }}>
                                                Ms</option>
                                            <option value="Dr"
                                                {{ old('Civilite', $employee->Civilite ?? '') == 'Dr' ? 'selected' : '' }}>
                                                Dr</option>
                                        </select>
                                        @error('Civilite')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Prenom_Nom" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="Prenom_Nom" name="Prenom_Nom"
                                            value="{{ old('Prenom_Nom', $employee->Prenom_Nom ?? '') }}" required>
                                        @error('Prenom_Nom')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="CIN" class="form-label">CIN</label>
                                        <input type="text" class="form-control" id="CIN" name="CIN"
                                            value="{{ old('CIN', $employee->CIN ?? '') }}">
                                        @error('CIN')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Date_Naissance" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="Date_Naissance" name="Date_Naissance"
                                            value="{{ old('Date_Naissance', $employee->Date_Naissance ?? '') }}">
                                        @error('Date_Naissance')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Sexe" class="form-label">Gender</label>
                                        <select class="form-select" id="Sexe" name="Sexe">
                                            <option value="Male"
                                                {{ old('Sexe', $employee->Sexe ?? '') == 'Male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="Female"
                                                {{ old('Sexe', $employee->Sexe ?? '') == 'Female' ? 'selected' : '' }}>
                                                Female</option>
                                        </select>
                                        @error('Sexe')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Etat_Civil" class="form-label">Marital Status</label>
                                        <select class="form-select" id="Etat_Civil" name="Etat_Civil">
                                            <option value="Single"
                                                {{ old('Etat_Civil', $employee->Etat_Civil ?? '') == 'Single' ? 'selected' : '' }}>
                                                Single</option>
                                            <option value="Married"
                                                {{ old('Etat_Civil', $employee->Etat_Civil ?? '') == 'Married' ? 'selected' : '' }}>
                                                Married</option>
                                            <option value="Divorced"
                                                {{ old('Etat_Civil', $employee->Etat_Civil ?? '') == 'Divorced' ? 'selected' : '' }}>
                                                Divorced</option>
                                            <option value="Widowed"
                                                {{ old('Etat_Civil', $employee->Etat_Civil ?? '') == 'Widowed' ? 'selected' : '' }}>
                                                Widowed</option>
                                        </select>
                                        @error('Etat_Civil')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Nombre_Enfants" class="form-label">Number of Children</label>
                                        <input type="number" class="form-control" id="Nombre_Enfants" name="Nombre_Enfants"
                                            value="{{ old('Nombre_Enfants', $employee->Nombre_Enfants ?? 0) }}"
                                            min="0">
                                        @error('Nombre_Enfants')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="mb-3">Employment Information</h5>

                                    <div class="mb-3">
                                        <label for="Fonction" class="form-label">Position</label>
                                        <input type="text" class="form-control" id="Fonction" name="Fonction"
                                            value="{{ old('Fonction', $employee->Fonction ?? '') }}">
                                        @error('Fonction')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Date_Recrutement" class="form-label">Hire Date</label>
                                        <input type="date" class="form-control" id="Date_Recrutement"
                                            name="Date_Recrutement"
                                            value="{{ old('Date_Recrutement', $employee->Date_Recrutement ?? '') }}">
                                        @error('Date_Recrutement')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Date_Service" class="form-label">Service Date</label>
                                        <input type="date" class="form-control" id="Date_Service" name="Date_Service"
                                            value="{{ old('Date_Service', $employee->Date_Service ?? '') }}">
                                        @error('Date_Service')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Echelle" class="form-label">Echelle</label>
                                        <select class="form-select" id="Echelle" name="Echelle">
                                            @foreach ($echelles as $echelle)
                                                <option value="{{ $echelle->Echelle }}"
                                                    {{ old('Echelle', $employee->Echelle ?? '') == $echelle->Echelle ? 'selected' : '' }}>
                                                    {{ $echelle->Echelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Echelle')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="echelon" class="form-label">Echelon</label>
                                        <select class="form-select" id="echelon" name="echelon">
                                            @foreach ($echelons as $echelon)
                                                <option value="{{ $echelon->echelon }}"
                                                    {{ old('echelon', $employee->echelon ?? '') == $echelon->echelon ? 'selected' : '' }}>
                                                    {{ $echelon->echelon }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('echelon')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="NIV" class="form-label">Niveau</label>
                                        <select class="form-select" id="NIV" name="NIV">
                                            @foreach ($niveaux as $niveau)
                                                <option value="{{ $niveau->NIV }}"
                                                    {{ old('NIV', $employee->NIV ?? '') == $niveau->NIV ? 'selected' : '' }}>
                                                    {{ $niveau->NIV }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('NIV')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Additional Information</h5>

                                    <div class="mb-3">
                                        <label for="Total_Gains" class="form-label">Total Gains</label>
                                        <input type="number" step="0.01" class="form-control" id="Total_Gains"
                                            name="Total_Gains"
                                            value="{{ old('Total_Gains', $employee->Total_Gains ?? 0) }}">
                                        @error('Total_Gains')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Etablissement" class="form-label">Establishment</label>
                                        <input type="text" class="form-control" id="Etablissement"
                                            name="Etablissement"
                                            value="{{ old('Etablissement', $employee->Etablissement ?? '') }}">
                                        @error('Etablissement')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="Assurance_Vie" class="form-label">Life Insurance</label>
                                        <input type="text" class="form-control" id="Assurance_Vie"
                                            name="Assurance_Vie"
                                            value="{{ old('Assurance_Vie', $employee->Assurance_Vie ?? '') }}">
                                        @error('Assurance_Vie')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="Email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="Email"
                                            name="Email"
                                            value="{{ old('Email', $employee->Utilisateur->email ?? '') }}">
                                        @error('Email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="Password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="Password"
                                            name="Password"
                                            value="{{ old('Password', $employee->Utilisateur->mot_de_passe_hash ?? '') }}">
                                        @error('Password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="Etat" class="form-label">Status</label>
                                        <select class="form-select" id="Etat" name="Etat">
                                            <option value="Actif"
                                                {{ old('Etat', $employee->Etat ?? '') == 'Actif' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="Inactif"
                                                {{ old('Etat', $employee->Etat ?? '') == 'Inactif' ? 'selected' : '' }}>
                                                Inactive</option>
                                            <option value="Retraité"
                                                {{ old('Etat', $employee->Etat ?? '') == 'Retraité' ? 'selected' : '' }}>
                                                Retired</option>
                                        </select>
                                        @error('Etat')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Photo" class="form-label">Photo</label>
                                        <input type="file" class="form-control" id="Photo" name="Photo">
                                        @error('Photo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @if (isset($employee) && $employee->Photo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $employee->Photo) }}" alt="Current Photo"
                                                    style="max-width: 100px; max-height: 100px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-save me-1"></i> Save
                                </button>
                                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
