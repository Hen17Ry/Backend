<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VoteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register',[\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login',[\App\Http\Controllers\AuthController::class, 'login']);
Route::get('logout',[\App\Http\Controllers\AuthController::class, 'logout']);
Route::post('create', [\App\Http\Controllers\VoteController::class, 'create']);
Route::get('/votes', [VoteController::class, 'index']);
Route::post('/publish-vote/{id}', [VoteController::class, 'publish']);
Route::get('/votes-en-cours', [VoteController::class, 'getVotesEnCours']);
Route::get('/candidats/{voteId}', [VoteController::class, 'getCandidatsByVote']);
Route::post('/voteForCandidate/{id}/{candidatId}', [VoteController::class, 'voteForCandidate']);
Route::get('/resultats-vote/{voteId}', [VoteController::class, 'getVoteResults']);
Route::delete('/delete-vote/{id}', [VoteController::class, 'deleteVote']);