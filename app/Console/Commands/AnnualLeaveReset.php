<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AnnualLeaveReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

    // app/Console/Commands/ReactivateEmployees.php
    public function handle()
    {
        $count = DB::table('personnel')
            ->join('ligne_conge', 'personnel.Matricule', '=', 'ligne_conge.Matricule')
            ->where('personnel.Etat', 'Inactif')
            ->where('ligne_conge.date_F', '<=', now())
            ->update(['personnel.Etat' => 'Actif']);

        $this->info("Reactivated {$count} employees");
    }
}
