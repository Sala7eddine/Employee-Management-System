<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $table = 'personnel';

    // Specify the primary key (optional if the primary key is 'id')
    protected $primaryKey = 'Matricule';

    // Specify if the primary key is not an incrementing integer
    public $incrementing = true;
    

    // Specify the data type of the primary key (optional if it's an integer)
    protected $keyType = 'string';

    // Define the fillable attributes (for mass assignment)
    protected $fillable = [
        'Matricule',
        'Civilite',
        'Prenom_Nom',
        'Specialite_Origine',
        'Fonction',
        'Date_Recrutement',
        'Date_Service',
        'CIN',
        'Date_Naissance',
        'Etat_Civil',
        'Nombre_Enfants',
        'Total_Gains',
        'Photo',
        'Sexe',
        'Etat',
        'Etablissement',
        'Assurance_Vie',
        'Echelle',
        'echelon',
        'NIV',
    ];

    // Define relationships
    public function echelle()
    {
        return $this->belongsTo(echelle::class, 'Echelle', 'Echelle');
    }

    public function grilleIndiciaire()
    {
        return $this->belongsTo(grilleIndiciaire::class, 'echelon', 'echelon');
    }

    public function niveau()
    {      
        return $this->belongsTo(niveau::class, 'NIV', 'NIV');
    }
    public function utilisateur()
{
    return $this->hasOne(Utilisateur::class, 'Matricule');
}
}