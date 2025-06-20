<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utilisateur extends Authenticatable
{
    use HasFactory;
    
    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';
    protected $fillable = [
        'email', 'mot_de_passe_hash', 'date_creation',
        'dernier_login', 'est_actif', 'Matricule', 'id_role'
    ];

    protected $hidden = [
        'mot_de_passe_hash', // Make sure this matches your password field name
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'date_creation' => 'datetime',
        'dernier_login' => 'datetime',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'Matricule');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    // Add this method to tell Laravel which field is your password
    public function getAuthPassword()
    {
        return $this->mot_de_passe_hash;
    }
}