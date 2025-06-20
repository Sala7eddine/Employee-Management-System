<?php

namespace Database\Factories;

use App\Models\Personnel;
use App\Models\Role;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
// use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UtilisateurFactory extends Factory
{
    protected $model = Utilisateur::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'mot_de_passe_hash' => bcrypt('password'),
            'date_creation' => Carbon::now(),
            'dernier_login' => $this->faker->optional(0.7)->dateTimeBetween('-1 month', 'now'),
            'est_actif' => $this->faker->boolean(80),
            'Matricule' => Personnel::factory(),
            'id_role' => Role::factory(),
        ];
    }
}