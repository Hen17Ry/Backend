<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Candidat;
use App\Models\Vote;
use App\Models\VotesUsers;

class VoteController extends Controller
{
    public function create(Request $request)
{
    try {
        $request->validate([
            'nom' => 'required',
            'description' => 'required',
            'candidats' => 'required|array|min:1',
            'candidats.*.nom' => 'required',
            'candidats.*.description' => 'required',
        ]);

        $vote = new Vote();
        $vote->nom = $request->input('nom');
        $vote->description = $request->input('description');
        $vote->visible = false;
        $vote->save();

        foreach ($request->input('candidats') as $candidatData) {
            $candidat = new Candidat();
            $candidat->nom = $candidatData['nom'];
            $candidat->description = $candidatData['description'];
            $candidat->score = 0;

            if (isset($candidatData['photo_path'])) {
                $candidat->photo_path = $candidatData['photo_path'];
            }

            $vote->candidats()->save($candidat);
        }

        Log::info('Vote créé avec succès');

        return response()->json(['message' => 'Vote créé avec succès'], 200);
    } catch (\Exception $e) {
        Log::error('Erreur lors de la création du vote: ' . $e->getMessage());
        return response()->json(['error' => 'Erreur lors de la création du vote'], 500);
    }
}


    public function index()
    {
        $votes = Vote::all();
        return response()->json($votes, 200);
    }

    public function publish($id)
    {
        try {
            $vote = Vote::findOrFail($id);
            $vote->update(['visible' => true]);

            return response()->json(['message' => 'Vote publié avec succès'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la publication du vote: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la publication du vote'], 500);
        }
    }

    public function getVotesEnCours()
    {
        try {
            $votesEnCours = Vote::where('visible', 1)->get();
            return response()->json($votesEnCours, 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des votes en cours: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des votes en cours'], 500);
        }
    }

    public function getCandidatsByVote($voteId)
    {
        try {
            $vote = Vote::find($voteId);
    
            if (!$vote) {
                return response()->json(['error' => 'Vote non trouvé'], 404);
            }
    
            $candidats = $vote->candidats;  // Utilisez la relation définie dans le modèle Vote
    
            return response()->json($candidats, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors du chargement des candidats'], 500);
        }
    }    

    public function voteForCandidate(Request $request, $userId, $candidatId)
{
    try {
        $userId = $userId;

        // Vérifier si l'utilisateur a déjà voté pour ce candidat dans ce vote
        $existingVote = VotesUsers::where('user_id', $userId)
                                   ->first();

        if ($existingVote) {
            return response()->json(['error' => 'Vous avez déjà voté pour ce candidat dans ce vote.'], 400);
        }
        else{
            // Enregistrer le vote dans la table 'votes_users'
        $vote = new VotesUsers();
        $vote->user_id = $userId;
        $vote->candidate_id = $candidatId;
        $vote->save();

        // Incrémenter le score du candidat
        $candidat = Candidat::find($candidatId);

        if ($candidat) {
            $candidat->score += 1;
            $candidat->save();
        }

        return response()->json(['message' => 'Vote enregistré avec succès.'], 200);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors du vote.'], 500);
    }
}

public function getVoteResults($voteId)
{
    try {
        // Récupérez les résultats du vote pour chaque candidat
        $results = Candidat::where('id_vote', $voteId)
            ->select('nom', 'score')
            ->get();

        return response()->json(['results' => $results], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la récupération des résultats.'], 500);
    }
}

public function deleteVote($id)
{
    try {
        // Recherchez le vote à supprimer avec ses relations (candidats et résultat)
        $vote = Vote::findOrFail($id);

        // Supprimez les candidats associés au vote
        Candidat::where('id_vote', $id)->delete();

        // Enfin, supprimez le vote lui-même
        $vote->delete();

        return response()->json(['message' => 'Vote et ses détails associés supprimés avec succès'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Échec de la suppression du vote'], 500);
    }
}


}
