<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

     protected $table = 'categories';

    // Specify the primary key (optional if the primary key is 'id')
    protected $primaryKey = 'num_cat';

    // Specify if the primary key is not an incrementing integer
    public $incrementing = true; // Set to true because 'num_cat' is an auto-incrementing ID

    protected $keyType = 'int'; // Set to 'int' because 'num_cat' is an integer


    protected $fillable = [
        'num_cat',
        'Categorie',
        'Indemnite_Logement',
    ];

}