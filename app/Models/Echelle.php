<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Echelle extends Model
{
    use HasFactory;

    protected $table = 'echelle';

    // Specify the primary key (optional if the primary key is 'id')
    protected $primaryKey = 'Echelle';

    // Specify if the primary key is not an incrementing integer
    public $incrementing = true; // Set to true because 'Echelle' is an auto-incrementing ID

    protected $keyType = 'int'; // Set to 'int' because 'Echelle' is an integer

    // Define the fillable attributes (for mass assignment)
    protected $fillable = [
        'Echelle',
        'Indemnite_Speciale',
        'num_cat', // Foreign key to the 'categories' table
    ];


    // Define relationships
    public function category()
    {
        return $this->belongsTo(Categorie::class, 'num_cat', 'num_cat');
    }

    public function personnels()
    {
        return $this->hasMany(Personnel::class, 'Echelle', 'Echelle');
    }
}