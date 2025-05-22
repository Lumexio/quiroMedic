<?php

namespace App\Http\Controllers;

use App\Models\Measure;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with organization-specific stats.
     */
    public function index()
    {
        $user = Auth::user();
        $organization = $user->organization;

        // Get organization-specific stats
        $stats = [
            'patientCount' => Patient::inOrganization($organization->id)->count(),
            'measureCount' => Measure::inOrganization($organization->id)->count(),
            'userCount' => User::inOrganization($organization->id)->count(),
            'recentActivityCount' => Measure::inOrganization($organization->id)
                ->where('created_at', '>=', now()->subDay())
                ->count(),
        ];

        return Inertia::render('Dashboard', [
            'organization' => $organization,
            'stats' => $stats,
        ]);
    }
}
