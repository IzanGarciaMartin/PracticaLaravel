<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectMemberTest extends TestCase
{
    use RefreshDatabase;

    private function createProject(User $owner): Project
    {
        return Project::create([
            'user_id' => $owner->id,
            'title' => 'Proyecto de prueba',
            'description' => 'Descripción del proyecto.',
            'deadline' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);
    }

    public function test_owner_can_add_a_participant(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = $this->createProject($owner);

        $response = $this->actingAs($owner)->post(
            route('projects.members.store', $project),
            [
                'email' => $member->email,
                'role' => 'member',
                'contribution_hours' => 5,
            ]
        );

        $response->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $member->id,
            'role' => 'member',
            'contribution_hours' => 5,
        ]);
    }

    public function test_cannot_add_a_duplicate_participant(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = $this->createProject($owner);

        $project->members()->attach($member->id, [
            'role' => 'member',
            'contribution_hours' => 3,
        ]);

        $response = $this->actingAs($owner)->post(
            route('projects.members.store', $project),
            [
                'email' => $member->email,
                'role' => 'admin',
                'contribution_hours' => 10,
            ]
        );

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $member->id,
            'role' => 'member',
            'contribution_hours' => 3,
        ]);

        $this->assertDatabaseCount('project_user', 1);
    }

    public function test_owner_cannot_be_added_as_a_participant(): void
    {
        $owner = User::factory()->create();

        $project = $this->createProject($owner);

        $response = $this->actingAs($owner)->post(
            route('projects.members.store', $project),
            [
                'email' => $owner->email,
                'role' => 'admin',
                'contribution_hours' => 0,
            ]
        );

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $project->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_owner_can_update_a_participant(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = $this->createProject($owner);

        $project->members()->attach($member->id, [
            'role' => 'member',
            'contribution_hours' => 5,
        ]);

        $response = $this->actingAs($owner)->patch(
            route('projects.members.update', [$project, $member]),
            [
                'role' => 'admin',
                'contribution_hours' => 15,
            ]
        );

        $response->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $member->id,
            'role' => 'admin',
            'contribution_hours' => 15,
        ]);
    }

    public function test_owner_can_remove_a_participant(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = $this->createProject($owner);

        $project->members()->attach($member->id, [
            'role' => 'member',
            'contribution_hours' => 5,
        ]);

        $response = $this->actingAs($owner)->delete(
            route('projects.members.destroy', [$project, $member])
        );

        $response->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $project->id,
            'user_id' => $member->id,
        ]);
    }

    public function test_admin_can_manage_project_participants(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        $newMember = User::factory()->create();

        $project = $this->createProject($owner);

        $project->members()->attach($admin->id, [
            'role' => 'admin',
            'contribution_hours' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('projects.members.store', $project),
            [
                'email' => $newMember->email,
                'role' => 'member',
                'contribution_hours' => 2,
            ]
        );

        $response->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $newMember->id,
            'role' => 'member',
            'contribution_hours' => 2,
        ]);
    }

    public function test_regular_member_cannot_manage_project_participants(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $newMember = User::factory()->create();

        $project = $this->createProject($owner);

        $project->members()->attach($member->id, [
            'role' => 'member',
            'contribution_hours' => 5,
        ]);

        $this->actingAs($member)
            ->post(route('projects.members.store', $project), [
                'email' => $newMember->email,
                'role' => 'member',
                'contribution_hours' => 0,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $project->id,
            'user_id' => $newMember->id,
        ]);
    }
}
