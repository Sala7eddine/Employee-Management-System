<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personnel;
use App\Models\Role;
use App\Models\Utilisateur;
use Carbon\Carbon;

class UtilisateurSeeder extends Seeder
{
    public function run()
    {
        // Create roles if they don't exist (double safety)
        $adminRole = \App\Models\Role::firstOrCreate(
            ['nom' => 'Administrateur'],
            ['nom' => 'Administrateur']
        );

        $employeRole = \App\Models\Role::firstOrCreate(
            ['nom' => 'Employé'],
            ['nom' => 'Employé']
        );

        // Admin user
        \App\Models\Utilisateur::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'mot_de_passe_hash' => bcrypt('admin123'),
                'date_creation' => now(),
                'dernier_login' => now(),
                'est_actif' => true,
                'Matricule' => null,
                'id_role' => $adminRole->id_role
            ]
        );

        // Employee users
        if (\App\Models\Personnel::exists()) {
            foreach (\App\Models\Personnel::all() as $emp) {
                // Generate clean email address
                $email = $this->generateCleanEmail($emp->Prenom_Nom);

                \App\Models\Utilisateur::updateOrCreate(
                    ['email' => $email],
                    [
                        'mot_de_passe_hash' => bcrypt('password'),
                        'date_creation' => now()->subDays(rand(1, 365)),
                        'dernier_login' => rand(0, 1) ? now()->subDays(rand(0, 30)) : null,
                        'est_actif' => rand(0, 10) > 2,
                        'Matricule' => $emp->Matricule,
                        'id_role' => $employeRole->id_role
                    ]
                );
            }
        }
    }

    // Add this method to your class
    private function generateCleanEmail(string $name): string
    {
        // Convert to lowercase
        $clean = strtolower($name);

        // Remove all whitespace
        $clean = preg_replace('/\s+/', '', $clean);

        // Remove accents and special characters
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
        $clean = preg_replace('/[^a-z0-9]/', '', $clean);

        // Ensure we have at least 3 characters before @
        if (strlen($clean) < 3) {
            $clean .= 'user';
        }

        return $clean . '@gmail.com';
    }
}
