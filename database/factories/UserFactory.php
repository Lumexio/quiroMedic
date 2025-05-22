<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            // Organization will be assigned in withOrganization method
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user belongs to a specific organization.
     */
    public function withOrganization(Organization $organization = null): static
    {
        return $this->state(function (array $attributes) use ($organization) {
            // If no organization is provided, create one
            if (!$organization) {
                $organization = Organization::factory()->create();
            }

            return [
                'organization_id' => $organization->id,
            ];
        });
    }

    /**
     * Indicate that the user is an organization owner.
     */
    public function asOrganizationOwner(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_organization_owner' => true,
        ]);
    }
}
