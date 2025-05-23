<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Measure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BodyMapController extends Controller
{
    /**
     * Show the body map with no specific patient.
     */
    public function index()
    {
        // Get a list of patients for selection
        $currentUser = Auth::user();
        $patients = Patient::inOrganization($currentUser->organization_id)->get();

        return Inertia::render('BodyMeasureMap', [
            'patients' => $patients,
        ]);
    }

    /**
     * Show the body map for a specific patient.
     */
    public function show($patientId)
    {
        $currentUser = Auth::user();

        // Get the patient
        $patient = Patient::findOrFail($patientId);

        // Check if patient belongs to user's organization
        if ($patient->organization_id !== $currentUser->organization_id) {
            return redirect()->route('patients.index')
                ->with('error', 'You do not have permission to view this patient.');
        }

        // Get the patient's measures
        $measures = Measure::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('BodyMeasureMap', [
            'patient' => $patient,
            'measures' => $measures,
            'patientId' => $patientId,
        ]);
    }
}
