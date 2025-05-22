<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'age',
        'gender',
        'weight',
        'education',
        'sport',
        'rest_hours',
        'user_id',
        'organization_id',
    ];

    /**
     * Get the user that created the patient.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organization that the patient belongs to.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the measures for the patient.
     */
    public function measures()
    {
        return $this->hasMany(Measure::class);
    }

    /**
     * Scope a query to only include patients of a specific organization.
     */
    public function scopeInOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }
}
