<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Creamos o actualizamos las cuentas de prueba.
        $izan = User::updateOrCreate(
            ['email' => 'izan@taskora.test'],
            [
                'name' => 'Izan',
                'password' => 'password',
            ]
        );

        $pedro = User::updateOrCreate(
            ['email' => 'pedro@taskora.test'],
            [
                'name' => 'Pedro',
                'password' => 'password',
            ]
        );

        $maria = User::updateOrCreate(
            ['email' => 'maria@taskora.test'],
            [
                'name' => 'María',
                'password' => 'password',
            ]
        );

        $javier = User::updateOrCreate(
            ['email' => 'javier@taskora.test'],
            [
                'name' => 'Javier',
                'password' => 'password',
            ]
        );

        // PROYECTO 1: Tiene un administrador y un participante.
        $project1 = Project::updateOrCreate(
            [
                'user_id' => $izan->id,
                'title' => 'Desarrollo de Taskora',
            ],
            [
                'description' => 'Desarrollo de una aplicación de gestión de proyectos.',
                'deadline' => now()->addDays(30),
                'status' => 'in_progress',
            ]
        );

        $project1->members()->sync([
            $pedro->id => [
                'role' => 'admin',
                'contribution_hours' => 12,
            ],
            $maria->id => [
                'role' => 'member',
                'contribution_hours' => 5,
            ],
        ]);

        // PROYECTO 2: Tiene participantes, pero ninguno es administrador.
        $project2 = Project::updateOrCreate(
            [
                'user_id' => $izan->id,
                'title' => 'Rediseño de la web',
            ],
            [
                'description' => 'Renovación de la interfaz de una página web.',
                'deadline' => now()->addDays(45),
                'status' => 'pending',
            ]
        );

        $project2->members()->sync([
            $pedro->id => [
                'role' => 'member',
                'contribution_hours' => 8,
            ],
            $maria->id => [
                'role' => 'member',
                'contribution_hours' => 4,
            ],
        ]);

        // PROYECTO 3: No tiene participantes.
        $project3 = Project::updateOrCreate(
            [
                'user_id' => $izan->id,
                'title' => 'Proyecto personal',
            ],
            [
                'description' => 'Proyecto sin participantes.',
                'deadline' => now()->addDays(60),
                'status' => 'pending',
            ]
        );

        $project3->members()->sync([]);

        // PROYECTO 4: Tiene varios administradores.
        $project4 = Project::updateOrCreate(
            [
                'user_id' => $izan->id,
                'title' => 'Plataforma colaborativa',
            ],
            [
                'description' => 'Desarrollo de una plataforma de trabajo en equipo.',
                'deadline' => now()->addDays(90),
                'status' => 'in_progress',
            ]
        );

        $project4->members()->sync([
            $pedro->id => [
                'role' => 'admin',
                'contribution_hours' => 10,
            ],
            $maria->id => [
                'role' => 'admin',
                'contribution_hours' => 20,
            ],
            $javier->id => [
                'role' => 'member',
                'contribution_hours' => 3,
            ],
        ]);

        // PROYECTO 5: Pertenece a Pedro.
        // Sirve para comprobar que Izan no puede acceder a él.
        $project5 = Project::updateOrCreate(
            [
                'user_id' => $pedro->id,
                'title' => 'Proyecto privado de Pedro',
            ],
            [
                'description' => 'Proyecto de otro propietario.',
                'deadline' => now()->addDays(20),
                'status' => 'pending',
            ]
        );

        $project5->members()->sync([
            $javier->id => [
                'role' => 'member',
                'contribution_hours' => 6,
            ],
        ]);
    }
}
