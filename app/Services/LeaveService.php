<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LeaveService
{
    /**
     * Get current leave balance for an employee
     */
    public static function getCurrentBalance(string $matricule): float
    {
        $currentYear = date('Y');
        
        $lastLeave = DB::table('ligne_conge')
            ->where('Matricule', $matricule)
            ->whereBetween('date_Demande', [
                strtotime("$currentYear-01-01"),
                strtotime("$currentYear-12-31 23:59:59")
            ])
            ->latest('date_Demande')
            ->first();

        return $lastLeave ? $lastLeave->Reliquat : 30.00;
    }

    /**
     * Calculate annual leave usage
     */
    public static function getAnnualUsage(string $matricule, int $year): float
    {
        return DB::table('ligne_conge')
            ->where('Matricule', $matricule)
            ->whereBetween('date_Demande', [
                strtotime("$year-01-01"),
                strtotime("$year-12-31 23:59:59")
            ])
            ->sum(DB::raw('DATEDIFF(date_F, date_D) + 1'));
    }
}