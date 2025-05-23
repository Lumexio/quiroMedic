<?php

namespace Database\Factories;

use App\Models\Measure;
use App\Models\Patient;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Measure>
 */
class MeasureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Measure::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define common body zones and their measurements
        $bodyMeasurements = [
            'codo' => [
                'name' => ['extensión', 'flexión'],
                'unit' => ['grados', 'cm'],
                'minValue' => 0,
                'maxValue' => 180,
            ],
            'escapohumeral' => [
                'name' => ['flexión', 'extensión', 'abducción', 'aducción'],
                'unit' => ['grados', 'cm'],
                'minValue' => 0,
                'maxValue' => 180,
            ],
            'raquis cervical' => [
                'name' => ['extensión', 'flexión', 'inclinación lateral derecha', 'inclinación lateral izquierda', 'rotación derecha', 'rotación izquierda'],
                'unit' => ['grados', 'cm'],
                'minValue' => 0,
                'maxValue' => 90,
            ],
            'raquis dorsolumbar' => [
                'name' => ['extensión', 'flexión', 'inclinación lateral derecha', 'inclinación lateral izquierda', 'rotación derecha', 'rotación izquierda'],
                'unit' => ['grados', 'cm'],
                'minValue' => 0,
                'maxValue' => 90,
            ],
            'rodilla' => [
                'name' => ['extensión', 'flexión'],
                'unit' => ['grados', 'cm'],
                'minValue' => 0,
                'maxValue' => 150,
            ],
            'tobillo' => [
                'name' => ['extensión', 'flexión', 'inversión', 'eversión'],
                'unit' => ['grados', 'cm'],
                'minValue' => 0,
                'maxValue' => 70,
            ],
            'muñeca' => [
                'name' => ['extensión', 'flexión', 'desviación radial', 'desviación cubital'],
                'unit' => ['grados', 'cm'],
                'minValue' => 0,
                'maxValue' => 90,
            ],
            'cadera' => [
                'name' => ['extensión', 'flexión', 'abducción', 'aducción', 'rotación interna', 'rotación externa'],
                'unit' => ['grados', 'cm'],
                'minValue' => 0,
                'maxValue' => 140,
            ],
        ];

        // Randomly select a body zone
        $bodyZone = fake()->randomElement(array_keys($bodyMeasurements));
        $bodyZoneData = $bodyMeasurements[$bodyZone];

        // Randomly select a measurement name for this body zone
        $measurementName = fake()->randomElement($bodyZoneData['name']);

        // Build the full measure name
        $name = "$bodyZone $measurementName";

        // Select a unit
        $unit = fake()->randomElement($bodyZoneData['unit']);

        // Generate a value appropriate for this measurement
        $value = fake()->randomFloat(1, $bodyZoneData['minValue'], $bodyZoneData['maxValue']);

        // Find or create a patient to associate with this measure
        $patient = Patient::inRandomOrder()->first();

        if (!$patient) {
            // If no patient exists, create one with its user and organization
            $organization = Organization::inRandomOrder()->first() ?? Organization::factory()->create();
            $user = User::where('organization_id', $organization->id)->first() ??
                User::factory()->create(['organization_id' => $organization->id]);

            $patient = Patient::factory()->create([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
            ]);
        }

        return [
            'name' => $name,
            'body_zone' => $bodyZone,
            'value' => $value,
            'unit' => $unit,
            'description' => fake()->paragraph(),
            'patient_id' => $patient->id,
            'user_id' => $patient->user_id,
            'organization_id' => $patient->organization_id,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Measure $measure) {
            // After making logic if needed
        })->afterCreating(function (Measure $measure) {
            // After creating logic if needed
        });
    }
}
