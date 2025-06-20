<?php

namespace App\Http\Controllers;

use App\Models\Echelle;
use App\Models\Employee;
use App\Models\GrilleIndiciaire;
use App\Models\Niveau;
use App\Models\Personnel;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Personnel::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('Prenom_Nom', 'like', "%$search%")
                    ->orWhere('Matricule', 'like', "%$search%")
                    ->orWhere('CIN', 'like', "%$search%");
            });
        }

        // Athentification

        // Filter by department
        if ($request->has('department') && $request->department != '') {
            $query->where('Specialite_Origine', $request->department);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('Etat', $request->status);
        }

        $employees = $query->paginate(5);
        $departments = Personnel::distinct('Specialite_Origine')->pluck('Specialite_Origine');

        // Statistics calculations
        $totalEmployees = Personnel::count();
        $newHires = Personnel::where('Date_Recrutement', '>=', Carbon::now()->subYear())->count();
        $onLeave = Personnel::where('Etat', 'Inactif')->count();

        // Department distribution data - single query for both counts and percentages
        $departmentStats = Personnel::select('Specialite_Origine')
            ->selectRaw('count(*) as count')
            ->groupBy('Specialite_Origine')
            ->get()
            ->pluck('count', 'Specialite_Origine');

        $departmentDistribution = $departmentStats->map(function ($count) use ($totalEmployees) {
            return $totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0;
        })->sortDesc()->toArray(); // Add ->toArray() here

        $departmentCounts = $departmentStats->toArray();

        // Department colors
        $deptColors = [
            'IT' => '#4f46e5',         // Purple
            'HR' => '#10b981',         // Emerald
            'Finance' => '#f59e0b',    // Amber
            'Marketing' => '#ec4899',  // Pink
            'Operations' => '#14b8a6', // Teal
            'Sales' => '#f97316',      // Orange
            'Engineering' => '#8b5cf6', // Violet
            'Customer Support' => '#0ea5e9', // Sky Blue
            'Product' => '#84cc16',    // Lime
            'Design' => '#d946ef',     // Fuchsia
            'Legal' => '#64748b',      // Slate
            'Administration' => '#f43f5e' // Rose
        ];
        $hiringTrends = [
            'Jan 2023' => ['IT' => 5, 'HR' => 3, 'Finance' => 2],
            'Feb 2023' => ['IT' => 7, 'HR' => 2, 'Finance' => 1],
            // ... more months
        ];
        // Get employee counts per department
        $departmentCounts = Personnel::select('Specialite_Origine')
            ->selectRaw('count(*) as employee_count')
            ->groupBy('Specialite_Origine')
            ->pluck('employee_count', 'Specialite_Origine')
            ->toArray();


        $hiringTrends = [];
        $departments = Personnel::distinct('Specialite_Origine')->pluck('Specialite_Origine');

        foreach ($departments as $dept) {
            $deptHires = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $count = Personnel::where('Specialite_Origine', $dept)
                    ->whereMonth('Date_Recrutement', $month->month)
                    ->whereYear('Date_Recrutement', $month->year)
                    ->count();
                $deptHires[$month->format('M')] = $count;
            }
            $hiringTrends[$dept] = $deptHires;
        }


        // Make sure we have colors for all departments
        foreach ($departmentDistribution as $dept => $percentage) {
            if (!isset($deptColors[$dept])) {
                $deptColors[$dept] = '#' . substr(md5($dept), 0, 6); // Generate random color
            }
        }

        // Hiring trends (simplified example)
        $hiringTrends = [
            'Jan' => 12,
            'Feb' => 15,
            'Mar' => 18,
            'Apr' => 10,
            'May' => 8,
            'Jun' => 14
        ];

        // Growth calculations (example values)
        $employeeGrowth = $totalEmployees > 0 ? round((($totalEmployees - ($totalEmployees * 0.968)) / $totalEmployees * 100)) : 0;
        $hireGrowth = $newHires > 0 ? round((($newHires - ($newHires * 0.88)) / $newHires * 100)) : 0;
        $openPositions = 28; // This would normally come from a positions/jobs table
        $positionChange = 4;
        $leaveChange = 0;
        $hiringGrowth = 48;
        $turnoverRate = 12;
        $netGrowth = 36;



        return view('pages.employees', compact(
            'employees',
            'departments',
            'totalEmployees',
            'newHires',
            'onLeave',
            'employeeGrowth',
            'hireGrowth',
            'openPositions',
            'positionChange',
            'leaveChange',
            'departmentDistribution',
            'deptColors',
            'hiringTrends',
            'hiringGrowth',
            'turnoverRate',
            'netGrowth',
            'departmentCounts',
            'hiringTrends',
            'departments',
        ));
    }

    public function create()
    {
        $departments = Personnel::distinct('Specialite_Origine')->pluck('Specialite_Origine');
        $echelles = Echelle::all();
        $echelons = GrilleIndiciaire::all();
        $niveaux = Niveau::all();

        return view('pages.add_or_edit', compact('departments', 'echelles', 'echelons', 'niveaux'));
    }

    public function store(Request $request)
    {
        // Validate employee data
        $validated = $request->validate([
            'Civilite' => 'required|string|max:10',
            'Prenom_Nom' => 'required|string|max:100',
            'Specialite_Origine' => 'nullable|string|max:100',
            'Fonction' => 'nullable|string|max:100',
            'Date_Recrutement' => 'nullable|date',
            'Date_Service' => 'nullable|date',
            'CIN' => 'required|string|max:20|unique:personnel,CIN', // Ensure CIN is unique
            'Date_Naissance' => 'nullable|date',
            'Etat_Civil' => 'nullable|string|max:20',
            'Nombre_Enfants' => 'nullable|integer',
            'Total_Gains' => 'nullable|numeric',
            'Photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Sexe' => 'required|string|max:10',
            'Etat' => 'nullable|string|max:20',
            'Etablissement' => 'nullable|string|max:100',
            'Assurance_Vie' => 'nullable|string|max:100',
            'Echelle' => 'required|exists:echelle,Echelle',
            'echelon' => 'required|exists:grille_indiciaire,echelon',
            'NIV' => 'required|exists:niveau,NIV',
        ]);

      // Handle photo upload
    if ($request->hasFile('Photo')) {
        $validated['Photo'] = $request->file('Photo')->store('employee-photos', 'public');
    }

    // Create the employee first to get the Matricule
    $employee = Personnel::create($validated);

    // Create the user account with default values
    Utilisateur::create([
        'email' => strtolower(str_replace(' ', '.', $validated['Prenom_Nom'])) . '@gmail.com',
        'mot_de_passe_hash' => Hash::make('password'), // Default password
        'date_creation' => now(),
        'dernier_login' => null,
        'est_actif' => true,
        'Matricule' => $employee->Matricule, // Use the created employee's Matricule
        'id_role' => 2, // Assuming 2 is the ID for 'employe' role
    ]);

    return redirect()->route('employees.index')
        ->with('success', 'Employee and user account created successfully.');
}


    public function details($Matricule)
    {
        $employee = Personnel::where('Matricule', $Matricule)->firstOrFail();

        // dd($employee);
        return view('pages.employee_Details', [
            'employee' => $employee,
            'documents' => $employee->documents, // Example related data
            'leaveHistory' => $employee->leaveRequests // Example related data
        ]);
    }

    public function edit($id)
    {
        $employee = Personnel::findOrFail($id);
        $departments = Personnel::distinct('Specialite_Origine')->pluck('Specialite_Origine');
        $echelles = Echelle::all();
        $echelons = GrilleIndiciaire::all();
        $niveaux = Niveau::all();

        return view('pages.add_or_edit', compact('employee', 'departments', 'echelles', 'echelons', 'niveaux'));
    }

   public function update(Request $request, $id)
{
    $employee = Personnel::findOrFail($id);
    
    $validated = $request->validate([
        'Civilite' => 'required|string|max:10',
        'Prenom_Nom' => 'required|string|max:100',
        'Specialite_Origine' => 'nullable|string|max:100',
        'Fonction' => 'nullable|string|max:100',
        'Date_Recrutement' => 'nullable|date',
        'Date_Service' => 'nullable|date',
        'CIN' => 'nullable|string|max:20|unique:personnel,CIN,' . $employee->Matricule . ',Matricule',
        'Date_Naissance' => 'nullable|date',
        'Etat_Civil' => 'nullable|string|max:20',
        'Nombre_Enfants' => 'nullable|integer',
        'Total_Gains' => 'nullable|numeric',
        'Photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Sexe' => 'required|string|max:10',
        'Etat' => 'nullable|string|max:20',
        'Etablissement' => 'nullable|string|max:100',
        'Assurance_Vie' => 'nullable|string|max:100',
        'Echelle' => 'required|exists:echelle,Echelle',
        'echelon' => 'required|exists:grille_indiciaire,echelon',
        'NIV' => 'required|exists:niveau,NIV',
    ]);

    $validatedUtilisateurs = $request->validate([
        'Email' => 'required',
        'Password' => 'required',
    ]);

    if ($request->hasFile('Photo')) {
        // Delete old photo if exists
        if ($employee->Photo) {
            Storage::disk('public')->delete($employee->Photo);
        }
        $validated['Photo'] = $request->file('Photo')->store('employee-photos', 'public');
    }

    $employee->update($validated);
    $employee->utilisateur->update($validatedUtilisateurs);


    return redirect()->route('employees.index')
        ->with('success', 'Employee updated successfully.');
}


    public function destroy($Matricule)
    {
        DB::beginTransaction();

        try {
            // Find the employee
            $employee = Personnel::findOrFail($Matricule);

            // Delete the associated user account if it exists
            if ($employee->utilisateur) {
                $employee->utilisateur->delete();
            }

            // Delete the employee
            $employee->delete();

            DB::commit();

            return redirect()->route('employees.index')
                ->with('success_sup', 'Employee and user account deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('employees.index')
                ->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }
}
