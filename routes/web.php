<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\auth\LoginController;

use App\Http\Controllers\Proponent\ProponentController;
use App\Http\Controllers\MenuController;
use  App\Http\Controllers\LocalSec\LocalSecretaryController;
use App\Http\Controllers\AcademicCouncil\AcadCouncilController;
use App\Http\Controllers\AdminCouncil\AdminCouncilController;









// Main Page Route
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/auth/google-login', [LoginController::class, 'handleGoogleLogin'])->name('auth.google.login');
Route::get('/api/menu', [MenuController::class, 'getMenu']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


Route::group(['prefix' => 'join-admin', 'middleware' => ['auth']], function () {

    Route::get('/agenda', [ProponentController::class , "agendaIndex"]);

});



Route::group(['prefix' => 'proponent', 'middleware' => ['auth']], function () {

    Route::post('/submit-proposal', [ProponentController::class, 'submitProposal'])
    ->middleware('checkRole:academic_council,admin_council') // Allow both roles to submit proposals
    ->name('proponent.submitProposal');


    Route::group(['middleware' => ['checkRole:academic_council']], function () {
        Route::get('/academic/submit-proposal', [ProponentController::class, 'index'])->name('academic.submit.proposal');
        Route::get('/academic/agenda', [ProponentController::class, 'agenda'])->name('academic.submit.proposal');
    });

    Route::group(['middleware' => ['checkRole:admin_council']], function () {
        Route::get('/admin/submit-proposal', [ProponentController::class, 'index'])->name('admin.submit.proposal');
    });
});


Route::group(['middleware' => ['checkRole:local_secretary']], function () {
    Route::get('/localsec-meetings', [LocalSecretaryController::class, 'index'])->name('localsec.meetings');
    Route::post('/localsec-meetings/select-year', [LocalSecretaryController::class, 'selectYear']);



    Route::get('/localSec/meeting/{encryptedId}/view', [LocalSecretaryController::class, 'viewMeetingProposals'])->name('localSec.meeting.view');
    Route::get('/localSec/meeting/{meetingId}/agenda-list', [LocalSecretaryController::class, 'showAgenda'])->name('localsec.agendaList');

    Route::post('/localSec/meeting/list-agenda', [LocalSecretaryController::class, 'listAgenda'])->name('localSec.listAgenda');

    Route::post('/localSec/meeting/post-agenda', [LocalSecretaryController::class, 'postAgenda'])->name('localSec.meeting.view');

    Route::post('/local-sec/store-meeting', [LocalSecretaryController::class, 'store'])->name('local-sec.store-meeting');
    Route::post('/local-sec/store-venue', [LocalSecretaryController::class, 'storeVenue'])->name('local-sec.store-venue');
Route::post('/local-sec/store-modality', [LocalSecretaryController::class, 'storeModality'])->name('local-sec.store-modality');

Route::get('/proposals/view/{id}', [LocalSecretaryController::class, 'viewProposal'])->name('viewProposal');


Route::put('/local-sec/meetings/{id}', [LocalSecretaryController::class, 'update'])->name('local-sec.update-meeting');
Route::get('/local-sec/meetings/{id}', [LocalSecretaryController::class, 'edit'])->name('local-sec.edit-meeting');
Route::delete('/local-sec/meetings/{id}', [LocalSecretaryController::class, 'destroy'])->name('meetings.destroy');



});

Route::group(['middleware' => ['checkRole:university_secretary']], function () {

});

Route::group(['middleware' => ['checkRole:board_sec']], function () {

});
