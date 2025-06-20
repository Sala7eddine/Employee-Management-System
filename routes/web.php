<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveDecisionController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\SalaryCertificateController;
use App\Http\Controllers\WorkCertificateController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

// Public routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/to-login', [AuthController::class, 'ToLogin'])->name('to-login');
    Route::get('/Logout', [AuthController::class, 'Logout'])->name('Logout');
});
// Test route (public)
Route::get('/Test', function () {
    return view('TestPage');
});

// Protected routes
Route::middleware(['auth'])->group(function () {

    Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');

    // Route::resource('employees', EmployeeController::class)->except(['index']);
    // routes/web.php
    Route::get('/employees/details/{Matricule}', [EmployeeController::class, 'details'])
        ->name('employees.details');

    // Route::post('/employees/update/{Matricule}', [EmployeeController::class, 'update'])
    //     ->name('employees.update');

    // For displaying the form to create a new employee
    Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');

    // For storing a new employee (POST)
    Route::post('employees/store', [EmployeeController::class, 'store'])->name('employees.store');

    // For displaying the form to edit an existing employee
    Route::get('employees/edit/{Matricule}', [EmployeeController::class, 'edit'])->name('employees.edit');

    // For updating an existing employee (PUT/PATCH)
    Route::put('employees/update/{Matricule}', [EmployeeController::class, 'update'])->name('employees.update');

    Route::delete('/employees/destroy/{employee}', [EmployeeController::class, 'destroy'])
        ->name('employees.destroy');

    Route::get('/personnel/{matricule}/salaire', [App\Http\Controllers\PersonnelControllerInformation::class, 'getSalaire']);



    // routes/api.php
    Route::get('/hiring-trends', function (Request $request) {
        $period = $request->query('period', 'monthly');
        $departments = Personnel::distinct('Specialite_Origine')->pluck('Specialite_Origine');
        $data = [];

        switch ($period) {
            case 'quarterly':
                // Implement quarterly logic
                break;
            case 'yearly':
                // Implement yearly logic
                break;
            default: // monthly
                $months = [];
                for ($i = 5; $i >= 0; $i--) {
                    $months[] = Carbon::now()->subMonths($i)->format('M');
                }

                $trends = [];
                foreach ($departments as $dept) {
                    $deptData = [];
                    for ($i = 5; $i >= 0; $i--) {
                        $month = Carbon::now()->subMonths($i);
                        $count = Personnel::where('Specialite_Origine', $dept)
                            ->whereMonth('Date_Recrutement', $month->month)
                            ->whereYear('Date_Recrutement', $month->year)
                            ->count();
                        $deptData[] = $count;
                    }
                    $trends[$dept] = $deptData;
                }

                $data = [
                    'months' => $months,
                    'trends' => $trends
                ];
        }

        return response()->json($data);
    });

    // Work certificates
    Route::get('/work-certificates', [WorkCertificateController::class, 'index'])->name('work-certificates.index');
    Route::post('/work-certificates', [WorkCertificateController::class, 'store'])->name('work-certificates.store');

    // Salary certificates
    Route::get('salary-certificates', [SalaryCertificateController::class, 'index'])->name('salary-certificates.index');
    Route::post('salary-certificates', [SalaryCertificateController::class, 'store'])->name('salary-certificates.store');

    // Leave requests
    Route::get('leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::post('leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::post('leave-requests/pdf', [LeaveRequestController::class, 'store2'])->name('leave-requests.store2');
    Route::delete('/leave-requests/destroy', [LeaveRequestController::class, 'destroy'])->name("leave-requests.destroy");
    Route::get('leave-requests/edit', [LeaveRequestController::class, 'edit'])->name('leave-requests.edit');
    Route::put('leave-requests/{leaveRequest}', [LeaveRequestController::class, 'update'])->name('leave-requests.update');
    // Admin-only routes
    // In routes/web.php
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('leave-decisions', [LeaveDecisionController::class, 'index'])->name('leave-decisions.index');
        Route::post('leave-decisions', [LeaveDecisionController::class, 'store'])->name('leave-decisions.store');
        Route::get('leave-decisions/{id}', [LeaveDecisionController::class, 'show'])->name('leave-decisions.show');
    });
});
