<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('leave:reset')
            ->yearlyOn(12, 31, '23:59');




        $schedule->call(function () {
            // Get all employees on leave that should be reactivated
            $employeesToReactivate = DB::table('personnel')
                ->join('ligne_conge', 'personnel.Matricule', '=', 'ligne_conge.Matricule')
                ->where('personnel.Etat', 'Inactif')
                ->where('ligne_conge.date_F', '<=', now())
                ->whereNull('ligne_conge.status_restored_at')
                ->select('personnel.Matricule', 'ligne_conge.num_cong')
                ->get();

            foreach ($employeesToReactivate as $employee) {
                DB::transaction(function () use ($employee) {
                    // Set status back to Active
                    DB::table('personnel')
                        ->where('Matricule', $employee->Matricule)
                        ->update(['Etat' => 'Actif']);

                    // Mark leave as processed
                    DB::table('ligne_conge')
                        ->where('num_cong', $employee->num_cong)
                        ->update(['status_restored_at' => now()]);
                });
            }
        })->daily(); // Runs daily at midnight
    }

    protected $commands = [
        \App\Console\Commands\ReactivateEmployeesCommand::class,
    ];



    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
