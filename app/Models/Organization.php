<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'uuid',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate UUID for new organizations
        static::creating(function ($organization) {
            $organization->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get the users that belong to the organization.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the patients that belong to the organization.
     */
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Get the measures that belong to the organization.
     */
    public function measures()
    {
        return $this->hasMany(Measure::class);
    }
}
