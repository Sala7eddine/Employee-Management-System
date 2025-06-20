<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneConge extends Model
{
    use HasFactory;

    protected $table = 'ligne_conge';

    // Specify the primary key (optional if the primary key is 'id')
    protected $primaryKey = 'date_Demande';

    // Specify if the primary key is not an incrementing integer
    public $incrementing = false; // Set to false because 'date_Demande' is not an auto-incrementing ID

    // Specify the data type of the primary key (optional if it's an integer)
    protected $keyType = 'string'; // Set to 'string' because 'date_Demande' is a string (or date)

    // Define the fillable attributes (for mass assignment)
    protected $fillable = [
        'date_Demande',
        'date_D',
        'date_F',
        'Reliquat',
        'Matricule', // Foreign key to the 'personnel' table
        'num_cong', // Foreign key to the 'conge' table
        'status'
    ];


    // Define relationships
    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'Matricule', 'Matricule');
    }

    public function conge()
    {
        return $this->belongsTo(Conge::class, 'num_cong', 'num_cong');
    }
    public function type()
    {
        return $this->belongsTo(Conge::class, 'num_cong', 'num_cong');
    }
}
