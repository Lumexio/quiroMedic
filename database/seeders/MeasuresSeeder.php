<?php

namespace Database\Seeders;

use App\Models\Measure;
use App\Models\Patient;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class MeasuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing organizations
        $organizations = Organization::all();

        if ($organizations->isEmpty()) {
            $this->command->info('No organizations found. Creating a default organization.');
            $organizations = Organization::factory()->count(1)->create();
        }

        foreach ($organizations as $organization) {
            // Get patients from this organization
            $patients = Patient::where('organization_id', $organization->id)->get();

            if ($patients->isEmpty()) {
                $this->command->info("No patients found for organization {$organization->name}. Creating patients.");

                // Find a user in this organization
                $user = User::where('organization_id', $organization->id)->first();

                if (!$user) {
                    $this->command->info("No users found for organization {$organization->name}. Creating a user.");
                    $user = User::factory()->create([
                        'organization_id' => $organization->id,
                    ]);
                }

                // Create patients for this organization
                $patients = Patient::factory()->count(3)->create([
                    'organization_id' => $organization->id,
                    'user_id' => $user->id,
                ]);
            }

            // For each patient, create several measures
            foreach ($patients as $patient) {
                // Create predefined specific measures for variety
                $this->createSpecificMeasures($patient);

                // Also create some random measures
                Measure::factory()->count(5)->create([
                    'patient_id' => $patient->id,
                    'user_id' => $patient->user_id,
                    'organization_id' => $organization->id,
                ]);
            }
        }

        $this->command->info('Measures seeded successfully!');
    }

    /**
     * Create specific pre-defined measures for a patient.
     */
    private function createSpecificMeasures($patient): void
    {
        // Define specific measures we want to create
        $specificMeasures = [
            [
                'name' => 'codo extensión derecho',
                'body_zone' => 'codo',
                'value' => 165,
                'unit' => 'grados',
                'description' => 'Medición de la extensión del codo derecho en posición supina',
            ],
            [
                'name' => 'codo flexión derecho',
                'body_zone' => 'codo',
                'value' => 145,
                'unit' => 'grados',
                'description' => 'Medición de la flexión del codo derecho en posición supina',
            ],
            [
                'name' => 'escapohumeral flexión izquierdo',
                'body_zone' => 'escapohumeral',
                'value' => 170,
                'unit' => 'grados',
                'description' => 'Medición de la flexión del hombro izquierdo en posición de pie',
            ],
            [
                'name' => 'escapohumeral extensión derecho',
                'body_zone' => 'escapohumeral',
                'value' => 50,
                'unit' => 'grados',
                'description' => 'Medición de la extensión del hombro derecho en posición de pie',
            ],
            [
                'name' => 'raquis cervical extensión',
                'body_zone' => 'raquis cervical',
                'value' => 60,
                'unit' => 'grados',
                'description' => 'Medición de la extensión cervical en posición sedente',
            ],
            [
                'name' => 'raquis cervical flexión',
                'body_zone' => 'raquis cervical',
                'value' => 45,
                'unit' => 'grados',
                'description' => 'Medición de la flexión cervical en posición sedente',
            ],
            [
                'name' => 'raquis cervical inclinación lateral derecha',
                'body_zone' => 'raquis cervical',
                'value' => 40,
                'unit' => 'grados',
                'description' => 'Medición de la inclinación lateral derecha cervical en posición sedente',
            ],
            [
                'name' => 'raquis cervical inclinación lateral izquierda',
                'body_zone' => 'raquis cervical',
                'value' => 38,
                'unit' => 'grados',
                'description' => 'Medición de la inclinación lateral izquierda cervical en posición sedente',
            ],
            [
                'name' => 'raquis dorsolumbar inclinación lateral izquierda',
                'body_zone' => 'raquis dorsolumbar',
                'value' => 35,
                'unit' => 'grados',
                'description' => 'Medición de la inclinación lateral izquierda dorsolumbar en posición de pie',
            ],
            [
                'name' => 'raquis dorsolumbar inclinación lateral derecha',
                'body_zone' => 'raquis dorsolumbar',
                'value' => 33,
                'unit' => 'grados',
                'description' => 'Medición de la inclinación lateral derecha dorsolumbar en posición de pie',
            ],
            [
                'name' => 'rodilla extensión izquierda',
                'body_zone' => 'rodilla',
                'value' => 175,
                'unit' => 'grados',
                'description' => 'Medición de la extensión de la rodilla izquierda en posición supina',
            ],
            [
                'name' => 'rodilla flexión derecha',
                'body_zone' => 'rodilla',
                'value' => 130,
                'unit' => 'grados',
                'description' => 'Medición de la flexión de la rodilla derecha en posición prona',
            ],
            [
                'name' => 'tobillo extensión izquierdo',
                'body_zone' => 'tobillo',
                'value' => 20,
                'unit' => 'grados',
                'description' => 'Medición de la extensión del tobillo izquierdo en posición sedente',
            ],
            [
                'name' => 'tobillo flexión derecho',
                'body_zone' => 'tobillo',
                'value' => 45,
                'unit' => 'grados',
                'description' => 'Medición de la flexión del tobillo derecho en posición sedente',
            ],
        ];

        // Get a few random measures from our list
        $selectedMeasures = array_rand($specificMeasures, min(5, count($specificMeasures)));

        // If only one selected, make it an array
        if (!is_array($selectedMeasures)) {
            $selectedMeasures = [$selectedMeasures];
        }

        // Create the selected measures
        foreach ($selectedMeasures as $index) {
            $measureData = $specificMeasures[$index];
            Measure::create([
                'name' => $measureData['name'],
                'body_zone' => $measureData['body_zone'],
                'value' => $measureData['value'],
                'unit' => $measureData['unit'],
                'description' => $measureData['description'],
                'patient_id' => $patient->id,
                'user_id' => $patient->user_id,
                'organization_id' => $patient->organization_id,
            ]);
        }
    }
}
