<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vote;

class Resultat extends Model
{
    use HasFactory;
    protected $fillable = ['id_vote'];

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }
}
