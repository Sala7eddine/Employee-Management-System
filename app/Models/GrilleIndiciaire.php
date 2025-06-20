<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrilleIndiciaire extends Model
{
    use HasFactory;

    
    protected $table = 'grille_indiciaire';

    // Specify the primary key (optional if the primary key is 'id')
    protected $primaryKey = 'echelon';

    // Specify if the primary key is not an incrementing integer
    public $incrementing = false; // Set to false if 'echelon' is not an auto-incrementing integer

    // Specify the data type of the primary key (optional if it's an integer)
    protected $keyType = 'string'; // Set to 'string' if 'echelon' is a string

    
    protected $fillable = [
        'echelon',
        'indice',
        'Echelle', // Foreign key to the 'echelle' table
    ];



    // Define relationships
    public function echelle()
    {
        return $this->belongsTo(Echelle::class, 'Echelle', 'Echelle');
    }

    public function personnels()
    {
        return $this->hasMany(Personnel::class, 'echelon', 'echelon');
    }
}