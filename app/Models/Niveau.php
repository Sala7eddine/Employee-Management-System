<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    use HasFactory;

    protected $table = 'niveau';

    // Specify the primary key (optional if the primary key is 'id')
    protected $primaryKey = 'NIV';

    // Specify if the primary key is not an incrementing integer
    public $incrementing = true; // Set to true because 'NIV' is an auto-incrementing ID

    protected $keyType = 'int'; // Set to 'int' because 'NIV' is an integer

    protected $fillable = [
        'NIV',
        'Formateur',
        'Indemnite_Qualification',
    ];

    public function personnels()
{
    return $this->hasMany(Personnel::class, 'NIV', 'NIV');
}
}