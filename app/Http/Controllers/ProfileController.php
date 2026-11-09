<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('ownedProjects.members');

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */

    public function destroy(Request $request): RedirectResponse
    {
        // Comprobamos la contraseña y que transfers sea un array.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
            'transfers' => ['sometimes', 'array'],
        ]);

        $user = $request->user();

        // Obtenemos los proyectos propios y sus participantes.
        $projects = $user->ownedProjects()
            ->with('members')
            ->get();

        $rules = [];
        $messages = [];

        // Comprobamos qué proyectos necesitan elegir un nuevo propietario.
        foreach ($projects as $project) {

            $eligibleMembers = $project->members
                ->where('id', '!=', $user->id);

            $admins = $eligibleMembers
                ->where('pivot.role', 'admin');

            // Si no hay participantes, el proyecto se eliminará.
            // Si hay participantes pero ningún administrador,
            // el usuario tendrá que elegir al nuevo propietario.
            if ($eligibleMembers->isNotEmpty() && $admins->isEmpty()) {

                $memberIds = $eligibleMembers->pluck('id')->all();

                $rules['transfers.' . $project->id] = [
                    'required',
                    'integer',
                    Rule::in($memberIds),
                ];

                $messages['transfers.' . $project->id . '.required'] =
                    "Debes elegir un nuevo responsable para {$project->title}.";

                $messages['transfers.' . $project->id . '.in'] =
                    "El responsable elegido para {$project->title} no es válido.";
            }
        }

        // Validamos las elecciones realizadas por el usuario.
        if (!empty($rules)) {
            $request->validateWithBag(
                'userDeletion',
                $rules,
                $messages
            );
        }

        // Ejecutamos todas las operaciones en una transacción.
        DB::transaction(function () use ($projects, $request, $user) {

            foreach ($projects as $project) {

                $eligibleMembers = $project->members
                    ->where('id', '!=', $user->id);

                // CASO 1: El proyecto no tiene participantes.
                // Lo eliminamos directamente.
                if ($eligibleMembers->isEmpty()) {
                    $project->delete();
                    continue;
                }

                $admins = $eligibleMembers
                    ->where('pivot.role', 'admin');

                // CASO 2: Hay administradores.
                // Elegimos al que más horas haya aportado.
                if ($admins->isNotEmpty()) {

                    $newOwner = $admins->sort(function ($a, $b) {

                        $hoursComparison =
                            $b->pivot->contribution_hours
                            <=> $a->pivot->contribution_hours;

                        // En caso de empate, gana el usuario con menor ID.
                        return $hoursComparison !== 0
                            ? $hoursComparison
                            : $a->id <=> $b->id;
                    })->first();

                } else {

                    // CASO 3: Hay participantes, pero ningún administrador.
                    // Utilizamos el participante elegido por el propietario.
                    $newOwnerId = $request->input(
                        'transfers.' . $project->id
                    );

                    $newOwner = $eligibleMembers->firstWhere(
                        'id',
                        (int) $newOwnerId
                    );
                }

                // Evitamos continuar si el nuevo propietario no es válido.
                if (!$newOwner) {
                    abort(422, 'No se pudo determinar un nuevo propietario.');
                }

                // Transferimos la propiedad del proyecto.
                $project->user_id = $newOwner->id;
                $project->save();

                // Nos aseguramos de que el nuevo propietario sea administrador.
                $project->members()->updateExistingPivot(
                    $newOwner->id,
                    ['role' => 'admin']
                );
            }

            // Cuando todos los proyectos están resueltos, eliminamos la cuenta.
            $user->delete();
        });

        // Cerramos la sesión y limpiamos los datos de sesión.
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

}
