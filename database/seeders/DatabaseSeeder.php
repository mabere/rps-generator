<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            EnglishEducationSeeder::class,
        ]);

        $user = User::factory()->create([
            'name' => 'Lecturer User',
            'email' => 'lecturer@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('lecturer');

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');
    }
}
