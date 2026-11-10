<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{


    public function index()
    {
        $user = Auth::user();

        $projects = Project::with('owner')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('members', function ($query) use ($user) {
                        $query->where('users.id', $user->id);
                    });
            })
            ->latest()
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }



    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $request->user()->ownedProjects()->create($validated);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyecto creado correctamente.');
    }


    public function show(Project $project)
    {
        $isOwner = $project->user_id === Auth::id();

        $isMember = $project->members()
            ->whereKey(Auth::id())
            ->exists();

        abort_unless($isOwner || $isMember, 403);

        $project->load(['owner', 'members']);

        return view('projects.show', compact('project'));
    }


    public function edit(Project $project)
    {
        $this->authorizeOwner($project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeOwner($project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $project->update($validated);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project)
    {
        $this->authorizeOwner($project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyecto eliminado correctamente.');
    }

    private function authorizeOwner(Project $project): void
    {
        abort_unless(
            $project->user_id === Auth::id(),
            403
        );
    }
}