
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    $ownedProjects = $user->ownedProjects()
        ->latest()
        ->take(5)
        ->get();

    $ownedProjectsCount = $user->ownedProjects()->count();

    $collaborationsCount = $user->projects()
        ->where('projects.user_id', '!=', $user->id)
        ->count();

    return view('dashboard', compact(
        'ownedProjects',
        'ownedProjectsCount',
        'collaborationsCount'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('projects', ProjectController::class);

    // Gestión de participantes de proyectos
    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])
        ->name('projects.members.store');

    Route::patch('/projects/{project}/members/{user}', [ProjectMemberController::class, 'update'])
        ->name('projects.members.update');

    Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])
        ->name('projects.members.destroy');
});

require __DIR__ . '/auth.php';
