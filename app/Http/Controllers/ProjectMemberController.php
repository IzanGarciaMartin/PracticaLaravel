<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectMemberController extends Controller
{
    /**
     * Añadir un participante al proyecto.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorizeManager($project);

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
            'role' => [
                'required',
                Rule::in(['admin', 'member']),
            ],
            'contribution_hours' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        // Buscamos al usuario mediante su correo electrónico.
        $member = User::where('email', $validated['email'])->firstOrFail();

        // El propietario ya tiene permisos de administrador.
        if ($member->id === $project->user_id) {
            return back()->withErrors([
                'email' => 'El propietario ya forma parte del proyecto.',
            ])->withInput();
        }

        // Comprobamos que no esté ya añadido.
        $alreadyMember = $project->members()
            ->whereKey($member->id)
            ->exists();

        if ($alreadyMember) {
            return back()->withErrors([
                'email' => 'Este usuario ya participa en el proyecto.',
            ])->withInput();
        }

        // Guardamos al participante en la tabla intermedia.
        $project->members()->attach($member->id, [
            'role' => $validated['role'],
            'contribution_hours' => $validated['contribution_hours'] ?? 0,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Participante añadido correctamente.');
    }

    /**
     * Modificar el rol y las horas de un participante.
     */
    public function update(Request $request, Project $project, User $user)
    {
        $this->authorizeManager($project);

        // Comprobamos que el usuario pertenezca al proyecto.
        $project->members()
            ->whereKey($user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'role' => [
                'required',
                Rule::in(['admin', 'member']),
            ],
            'contribution_hours' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $project->members()->updateExistingPivot(
            $user->id,
            $validated
        );

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Participante actualizado correctamente.');
    }

    /**
     * Eliminar un participante del proyecto.
     */
    public function destroy(Project $project, User $user)
    {
        $this->authorizeManager($project);

        // Comprobamos que sea participante.
        $project->members()
            ->whereKey($user->id)
            ->firstOrFail();

        $project->members()->detach($user->id);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Participante eliminado correctamente.');
    }

    /**
     * Comprobar si el usuario actual puede gestionar participantes.
     */
    private function authorizeManager(Project $project): void
    {
        $isOwner = $project->user_id === Auth::id();

        $isAdmin = $project->members()
            ->whereKey(Auth::id())
            ->wherePivot('role', 'admin')
            ->exists();

        abort_unless($isOwner || $isAdmin, 403);
    }
}
