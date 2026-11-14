<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    private function createProject(User $owner, string $title = 'Proyecto de prueba'): Project
    {
        return Project::create([
            'user_id' => $owner->id,
            'title' => $title,
            'description' => 'Descripción del proyecto.',
            'deadline' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);
    }

    private function addMember(
        Project $project,
        User $user,
        string $role = 'member',
        int $hours = 0
    ): void {
        $project->members()->attach($user->id, [
            'role' => $role,
            'contribution_hours' => $hours,
        ]);
    }

    public function test_project_is_transferred_to_admin_with_most_contribution_hours(): void
    {
        $owner = User::factory()->create();
        $pedro = User::factory()->create();
        $maria = User::factory()->create();
        $javier = User::factory()->create();

        $project = $this->createProject($owner);

        $this->addMember($project, $pedro, 'admin', 10);
        $this->addMember($project, $maria, 'admin', 20);
        $this->addMember($project, $javier, 'member', 5);

        $this->actingAs($owner)->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $maria->id,
        ]);

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $maria->id,
            'role' => 'admin',
            'contribution_hours' => 20,
        ]);

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $pedro->id,
            'role' => 'admin',
            'contribution_hours' => 10,
        ]);

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $javier->id,
            'role' => 'member',
            'contribution_hours' => 5,
        ]);
    }

    public function test_lower_user_id_wins_when_admins_have_equal_contribution_hours(): void
    {
        $owner = User::factory()->create();
        $firstAdmin = User::factory()->create();
        $secondAdmin = User::factory()->create();

        $project = $this->createProject($owner);

        $this->addMember($project, $firstAdmin, 'admin', 15);
        $this->addMember($project, $secondAdmin, 'admin', 15);

        $this->actingAs($owner)->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $firstAdmin->id,
        ]);
    }

    public function test_owner_can_select_new_owner_when_project_has_no_admins(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $otherMember = User::factory()->create();

        $project = $this->createProject($owner);

        $this->addMember($project, $member, 'member', 8);
        $this->addMember($project, $otherMember, 'member', 3);

        $response = $this->actingAs($owner)->delete(
            route('profile.destroy'),
            [
                'password' => 'password',
                'transfers' => [
                    $project->id => $member->id,
                ],
            ]
        );

        $response->assertRedirect('/');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $member->id,
        ]);

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $member->id,
            'role' => 'admin',
            'contribution_hours' => 8,
        ]);
    }

    public function test_account_deletion_requires_owner_selection_when_project_has_no_admins(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = $this->createProject($owner);

        $this->addMember($project, $member);

        $this->actingAs($owner)
            ->delete(route('profile.destroy'), [
                'password' => 'password',
            ])
            ->assertSessionHasErrorsIn(
                'userDeletion',
                'transfers.' . $project->id
            );

        $this->assertDatabaseHas('users', [
            'id' => $owner->id,
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_account_deletion_rejects_invalid_transfer_selection(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $outsider = User::factory()->create();

        $project = $this->createProject($owner);

        $this->addMember($project, $member);

        $this->actingAs($owner)
            ->delete(route('profile.destroy'), [
                'password' => 'password',
                'transfers' => [
                    $project->id => $outsider->id,
                ],
            ])
            ->assertSessionHasErrorsIn(
                'userDeletion',
                'transfers.' . $project->id
            );

        $this->assertDatabaseHas('users', [
            'id' => $owner->id,
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_project_without_participants_is_deleted_automatically(): void
    {
        $owner = User::factory()->create();

        $project = $this->createProject($owner);

        $this->actingAs($owner)->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }

    public function test_account_deletion_handles_multiple_projects_correctly(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        $member = User::factory()->create();

        $projectWithAdmin = $this->createProject($owner, 'Proyecto con administrador');
        $projectWithoutAdmin = $this->createProject($owner, 'Proyecto sin administrador');
        $emptyProject = $this->createProject($owner, 'Proyecto vacío');

        $this->addMember($projectWithAdmin, $admin, 'admin', 20);
        $this->addMember($projectWithAdmin, $member, 'member', 5);

        $this->addMember($projectWithoutAdmin, $member, 'member', 8);

        $response = $this->actingAs($owner)->delete(
            route('profile.destroy'),
            [
                'password' => 'password',
                'transfers' => [
                    $projectWithoutAdmin->id => $member->id,
                ],
            ]
        );

        $response->assertRedirect('/');

        $this->assertDatabaseHas('projects', [
            'id' => $projectWithAdmin->id,
            'user_id' => $admin->id,
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $projectWithoutAdmin->id,
            'user_id' => $member->id,
        ]);

        $this->assertDatabaseMissing('projects', [
            'id' => $emptyProject->id,
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $owner->id,
        ]);
    }

    public function test_account_is_deleted_and_user_is_logged_out(): void
    {
        $queries = [];

        DB::listen(function ($query) {

            if (
                str_contains($query->sql, 'insert into "users"')
                && str_contains($query->sql, '"id") values')
            ) {
                dump([
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'trace' => collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20))
                        ->map(
                            fn($frame) =>
                                ($frame['file'] ?? '') . ':' . ($frame['line'] ?? '')
                        )
                        ->all(),
                ]);
            }
        });
        $owner = User::factory()->create();

        $response = $this->actingAs($owner)->delete(
            route('profile.destroy'),
            [
                'password' => 'password',
            ]
        );
        dump($queries);
        $response->assertRedirect('/');

        dump([
            'transaction_level' => DB::connection()->transactionLevel(),
            'pdo_id' => spl_object_id(DB::connection()->getPdo()),
            'user_exists' => DB::table('users')
                ->where('id', $owner->id)
                ->exists(),
            'user_count' => DB::table('users')->count(),
        ]);
        dump([
            'response_status' => $response->status(),
            'response_location' => $response->headers->get('Location'),
            'database_connection' => DB::connection()->getName(),
            'database_name' => DB::connection()->getDatabaseName(),
            'user_exists' => DB::table('users')
                ->where('id', $owner->id)
                ->exists(),
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $owner->id,
        ]);

        $this->assertGuest();
        ;

        $this->assertGuest();
    }
}
