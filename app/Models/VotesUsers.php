<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotesUsers extends Model
{
    use HasFactory;

    protected $table = 'votes_users';

    protected $fillable = ['user_id', 'candidate_id'];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'candidat_id');
    }
}
