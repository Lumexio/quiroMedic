<?php

namespace App\Http\Controllers;

use App\Models\Measure;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MeasureController extends Controller
{
    /**
     * Display a listing of measures.
     */
    public function index()
    {
        // Only fetch measures from the current user's organization
        $currentUser = Auth::user();
        $measures = Measure::inOrganization($currentUser->organization_id)->get();

        return Inertia::render('MeasureView', [
            'measures' => $measures,
        ]);
    }

    /**
     * Create a new measure with pre-selected patient.
     */
    public function create(Request $request)
    {
        // Only fetch patients from the current user's organization
        $currentUser = Auth::user();
        $patients = Patient::inOrganization($currentUser->organization_id)->get();

        // Check if a patient ID was provided in the query string
        $selectedPatientId = $request->query('patient');

        return Inertia::render('CreateMeasure', [
            'patients' => $patients,
            'selectedPatientId' => $selectedPatientId,
        ]);
    }

    /**
     * Store a newly created measure in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'body_zone' => 'required|string|max:255',
            'value' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'patient_id' => 'required|exists:patients,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Max 2MB image
        ]);

        // Add user_id and organization_id
        $currentUser = Auth::user();
        $validated['user_id'] = $currentUser->id;
        $validated['organization_id'] = $currentUser->organization_id;

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('measures', 'public');
            $validated['image_path'] = $path;
        }

        // Create measure
        Measure::create($validated);

        return redirect()->route('measures.index')
            ->with('success', 'Measure created successfully.');
    }

    /**
     * Display the specified measure.
     */
    public function show($id)
    {
        $measure = Measure::findOrFail($id);

        // Check if measure belongs to user's organization
        $currentUser = Auth::user();
        if ($measure->organization_id !== $currentUser->organization_id) {
            return redirect()->route('measures.index')
                ->with('error', 'You do not have permission to view this measure.');
        }

        // Get the associated patient
        $patient = Patient::findOrFail($measure->patient_id);

        return Inertia::render('MeasureDetail', [
            'measure' => $measure,
            'patient' => $patient,
        ]);
    }

    /**
     * Show the form for editing the specified measure.
     */
    public function edit($id)
    {
        $measure = Measure::findOrFail($id);
        $patients = Patient::all();

        return Inertia::render('EditMeasure', [
            'measure' => $measure,
            'patients' => $patients,
        ]);
    }

    /**
     * Update the specified measure in storage.
     */
    public function update(Request $request, $id)
    {
        $measure = Measure::findOrFail($id);

        // Check if measure belongs to user's organization
        if ($measure->organization_id !== Auth::user()->organization_id) {
            return redirect()->route('measures.index')
                ->with('error', 'You do not have permission to update this measure.');
        }

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'body_zone' => 'required|string|max:255',
            'value' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'patient_id' => 'required|exists:patients,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Max 2MB image
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($measure->image_path) {
                Storage::disk('public')->delete($measure->image_path);
            }

            $path = $request->file('image')->store('measures', 'public');
            $validated['image_path'] = $path;
        }

        // Update measure
        $measure->update($validated);

        return redirect()->route('measures.index')
            ->with('success', 'Measure updated successfully.');
    }

    /**
     * Remove the specified measure from storage.
     */
    public function delete($id)
    {
        $measure = Measure::findOrFail($id);
        $measure->delete();

        return redirect()->route('measures.index')
            ->with('success', 'Measure deleted successfully.');
    }
}
