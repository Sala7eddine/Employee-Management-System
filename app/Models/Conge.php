<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;

    protected $table = 'conge';
    protected $primaryKey = 'num_cong';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'num_cong',
        'type_cong',
    ];

    public function ligneConges()
    {
        return $this->hasMany(LigneConge::class, 'num_cong', 'num_cong');
    }
}