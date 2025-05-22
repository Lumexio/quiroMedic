<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Create a user with an organization if one doesn't exist
        $user = User::factory()->create();

        // If the user doesn't have an organization, create one
        if (!$user->organization_id) {
            $organization = Organization::create([
                'name' => fake()->company(),
                'slug' => fake()->slug(),
            ]);
            $user->update(['organization_id' => $organization->id]);
        }

        return [
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'age' => fake()->numberBetween(1, 100),
            'gender' => fake()->randomElement(['male', 'female']),
            'weight' => fake()->randomFloat(2, 30, 150),
            'education' => fake()->word(),
            'sport' => fake()->word(),
            'rest_hours' => fake()->numberBetween(1, 12),
            'user_id' => $user->id,
            'organization_id' => $user->organization_id,
        ];
    }
}
