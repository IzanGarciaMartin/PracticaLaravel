<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(5)->create();

        foreach ($users as $user) {
            $projects = Project::factory(2)->create([
                'user_id' => $user->id,
            ]);

            foreach ($projects as $project) {
                $project->members()->attach($users->random()->id, [
                    'role' => 'developer',
                    'contribution_hours' => rand(1, 40),
                ]);
            }
        }
    }
}