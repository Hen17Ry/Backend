<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vote;
use App\Models\Score;

class Candidat extends Model
{
    use HasFactory;

    protected $fillable = ['id_vote', 'nom', 'photo_path', 'description', 'score'];

    public function vote()
    {
        return $this->belongsTo(Vote::class, 'id_vote');
    }
}
