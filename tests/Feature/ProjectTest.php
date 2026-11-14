<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    private function createProject(User $owner): Project
    {
        return Project::create([
            'user_id' => $owner->id,
            'title' => 'Proyecto de prueba',
            'description' => 'Descripción del proyecto de prueba.',
            'deadline' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);
    }

    public function test_guest_cannot_access_projects(): void
    {
        $response = $this->get(route('projects.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_only_sees_projects_they_own_or_join(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $outsider = User::factory()->create();

        $ownedProject = $this->createProject($owner);

        $sharedProject = Project::create([
            'user_id' => $outsider->id,
            'title' => 'Proyecto compartido',
            'description' => 'Proyecto con participantes.',
            'deadline' => now()->addDays(30)->toDateString(),
            'status' => 'in_progress',
        ]);

        $sharedProject->members()->attach($member->id, [
            'role' => 'member',
            'contribution_hours' => 5,
        ]);

        $this->actingAs($owner)
            ->get(route('projects.index'))
            ->assertOk()
            ->assertSee('Proyecto de prueba')
            ->assertDontSee('Proyecto compartido');

        $this->actingAs($member)
            ->get(route('projects.index'))
            ->assertOk()
            ->assertSee('Proyecto compartido')
            ->assertDontSee('Proyecto de prueba');
    }

    public function test_owner_can_create_project(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            route('projects.store'),
            [
                'title' => 'Nuevo proyecto',
                'description' => 'Descripción del nuevo proyecto.',
                'deadline' => now()->addDays(15)->toDateString(),
                'status' => 'in_progress',
            ]
        );

        $response->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'title' => 'Nuevo proyecto',
            'status' => 'in_progress',
        ]);
    }

    public function test_project_creation_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            route('projects.store'),
            [
                'title' => '',
                'description' => '',
                'deadline' => now()->subDay()->toDateString(),
                'status' => 'invalid',
            ]
        );

        $response->assertSessionHasErrors([
            'title',
            'description',
            'deadline',
            'status',
        ]);

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_owner_can_update_project(): void
    {
        $owner = User::factory()->create();
        $project = $this->createProject($owner);

        $response = $this->actingAs($owner)->put(
            route('projects.update', $project),
            [
                'title' => 'Proyecto actualizado',
                'description' => 'Nueva descripción.',
                'deadline' => now()->addDays(40)->toDateString(),
                'status' => 'completed',
            ]
        );

        $response->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Proyecto actualizado',
            'status' => 'completed',
        ]);
    }

    public function test_owner_can_delete_project(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = $this->createProject($owner);

        $project->members()->attach($member->id, [
            'role' => 'member',
            'contribution_hours' => 3,
        ]);

        $response = $this->actingAs($owner)
            ->delete(route('projects.destroy', $project));

        $response->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $project->id,
        ]);
    }

    public function test_member_can_view_but_cannot_modify_project(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = $this->createProject($owner);

        $project->members()->attach($member->id, [
            'role' => 'member',
            'contribution_hours' => 5,
        ]);

        $this->actingAs($member)
            ->get(route('projects.show', $project))
            ->assertOk();

        $this->actingAs($member)
            ->get(route('projects.edit', $project))
            ->assertForbidden();

        $this->actingAs($member)
            ->put(route('projects.update', $project), [
                'title' => 'Intento de modificación',
                'description' => 'No debería funcionar.',
                'deadline' => now()->addDays(30)->toDateString(),
                'status' => 'pending',
            ])
            ->assertForbidden();

        $this->actingAs($member)
            ->delete(route('projects.destroy', $project))
            ->assertForbidden();
    }

    public function test_unrelated_user_cannot_view_project(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();

        $project = $this->createProject($owner);

        $this->actingAs($outsider)
            ->get(route('projects.show', $project))
            ->assertForbidden();
    }
}
