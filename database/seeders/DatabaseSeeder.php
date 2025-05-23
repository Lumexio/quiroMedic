<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Patient;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure proper migration sequence
        if (!Schema::hasTable('organizations')) {
            $this->command->error('Organizations table does not exist. Please run migrations first.');
            return;
        }

        // First run the roles and permissions seeder
        $this->call(RolesAndPermissionsSeeder::class);

        // Create two separate organizations
        $organization1 = Organization::create([
            'name' => 'QuiroClinic One',
            'slug' => 'quiroclinic-one',
            'uuid' => Str::uuid(),
        ]);

        $organization2 = Organization::create([
            'name' => 'QuiroClinic Two',
            'slug' => 'quiroclinic-two',
            'uuid' => Str::uuid(),
        ]);

        // Create users for organization 1
        $testUser = User::factory()->create([
            'name' => 'Admin User',
            'lastname' => 'One',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'organization_id' => $organization1->id,
            'is_organization_owner' => true,
        ]);

        $testUser2 = User::factory()->create([
            'name' => 'Medic User',
            'lastname' => 'One',
            'email' => 'medic@example.com',
            'password' => Hash::make('password'),
            'organization_id' => $organization1->id,
        ]);

        // Create users for organization 2
        $testUser3 = User::factory()->create([
            'name' => 'Admin User',
            'lastname' => 'Two',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password'),
            'organization_id' => $organization2->id,
            'is_organization_owner' => true,
        ]);

        $testUser4 = User::factory()->create([
            'name' => 'Medic User',
            'lastname' => 'Two',
            'email' => 'medic2@example.com',
            'password' => Hash::make('password'),
            'organization_id' => $organization2->id,
        ]);

        // Assign roles to users
        $testUser->assignRole('admin');
        $testUser2->assignRole('medic');
        $testUser3->assignRole('admin');
        $testUser4->assignRole('medic');

        // Create patients for each organization
        for ($i = 0; $i < 5; $i++) {
            Patient::factory()->create([
                'user_id' => $testUser->id,
                'organization_id' => $organization1->id,
            ]);

            Patient::factory()->create([
                'user_id' => $testUser3->id,
                'organization_id' => $organization2->id,
            ]);
        }

        // Run the measures seeder to add measures for the patients
        $this->call(MeasuresSeeder::class);
    }
}
