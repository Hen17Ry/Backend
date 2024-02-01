<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Candidat;
use App\Models\Resultat;

class Vote extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'description', 'visible'];

    public function candidats()
    {
        return $this->hasMany(Candidat::class, 'id_vote');
    }

}

