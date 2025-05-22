<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Measure;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     */
    public function index()
    {
        // Only fetch patients from the current user's organization
        $currentUser = Auth::user();
        $patients = Patient::inOrganization($currentUser->organization_id)->get();

        return Inertia::render('PatientsView', [
            'patients' => $patients,
        ]);
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return Inertia::render('CreatePatient');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|string|in:male,female,other',
            'weight' => 'required|numeric|min:0',
            'education' => 'required|string|max:255',
            'sport' => 'required|string|max:255',
            'rest_hours' => 'required|integer|min:0|max:24',
        ]);

        // Add user_id and organization_id to the validated data
        $currentUser = Auth::user();
        $validated['user_id'] = $currentUser->id;
        $validated['organization_id'] = $currentUser->organization_id;

        // Create patient
        Patient::create($validated);

        // Return a JSON response for API calls or redirect for web requests
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Patient created successfully'], 201);
        }

        return redirect()->route('patients.index')
            ->with('success', 'Patient created successfully.');
    }

    /**
     * Display the specified patient.
     */
    public function show($id)
    {
        $patient = Patient::findOrFail($id);

        // Check if patient belongs to user's organization
        $currentUser = Auth::user();
        if ($patient->organization_id !== $currentUser->organization_id) {
            return redirect()->route('patients.index')
                ->with('error', 'You do not have permission to view this patient.');
        }

        // Get the patient's measures
        $measures = Measure::where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('PatientDetail', [
            'patient' => $patient,
            'measures' => $measures,
        ]);
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);

        return Inertia::render('EditPatient', [
            'patient' => $patient,
        ]);
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|string|in:male,female,other',
            'weight' => 'required|numeric|min:0',
            'education' => 'required|string|max:255',
            'sport' => 'required|string|max:255',
            'rest_hours' => 'required|integer|min:0|max:24',
        ]);

        // Update patient
        $patient->update($validated);

        // Return a JSON response for API calls or redirect for web requests
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Patient updated successfully']);
        }

        return redirect()->route('patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function delete($id)
    {
        $patient = Patient::findOrFail($id);

        // Delete the patient
        $patient->delete();

        // Redirect with success message
        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
