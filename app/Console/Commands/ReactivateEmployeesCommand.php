<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReactivateEmployeesCommand extends Command
{
    protected $signature = 'employees:reactivate';
    protected $description = 'Reactivate employees whose leave period has ended';

    public function handle()
    {
        $count = DB::table('personnel')
            ->join('ligne_conge', 'personnel.Matricule', '=', 'ligne_conge.Matricule')
            ->where('personnel.Etat', 'Inactif')
            ->where('ligne_conge.date_F', '<=', now())
            ->update(['personnel.Etat' => 'Actif']);

        $this->info("Successfully reactivated {$count} employees");
        
        return 0;
    }
}