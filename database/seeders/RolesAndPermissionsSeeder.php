<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles with explicit guard_name
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $medicRole = Role::create(['name' => 'medic', 'guard_name' => 'web']);


        // Create patient permissions with explicit guard_name
        $createPatientPermission = Permission::create(['name' => 'create-patient', 'guard_name' => 'web']);
        $editPatientPermission = Permission::create(['name' => 'edit-patient', 'guard_name' => 'web']);
        $deletePatientPermission = Permission::create(['name' => 'delete-patient', 'guard_name' => 'web']);
        $viewPatientPermission = Permission::create(['name' => 'view-patient', 'guard_name' => 'web']);

        // Create measure permissions with explicit guard_name
        $createMeasurePermission = Permission::create(['name' => 'create-measure', 'guard_name' => 'web']);
        $editMeasurePermission = Permission::create(['name' => 'edit-measure', 'guard_name' => 'web']);
        $deleteMeasurePermission = Permission::create(['name' => 'delete-measure', 'guard_name' => 'web']);
        $viewMeasurePermission = Permission::create(['name' => 'view-measure', 'guard_name' => 'web']);

        // Create user permissions with explicit guard_name
        $createUserPermission = Permission::create(['name' => 'create-user', 'guard_name' => 'web']);
        $editUserPermission = Permission::create(['name' => 'edit-user', 'guard_name' => 'web']);
        $deleteUserPermission = Permission::create(['name' => 'delete-user', 'guard_name' => 'web']);


        // Assign measure permissions to roles
        $adminRole->givePermissionTo([
            $createMeasurePermission,
            $editMeasurePermission,
            $deleteMeasurePermission
        ]);
        $medicRole->givePermissionTo([
            $createMeasurePermission,
            $editMeasurePermission,
            $deleteMeasurePermission
        ]);

        // Assign patient permissions to roles
        $adminRole->givePermissionTo([
            $createPatientPermission,
            $editPatientPermission,
            $deletePatientPermission,
            $viewPatientPermission
        ]);

        $medicRole->givePermissionTo([
            $createPatientPermission,
            $editPatientPermission,
            $deletePatientPermission, // Grant delete permission to medics
            $viewPatientPermission,
            $createMeasurePermission,
            $editMeasurePermission,
            $deleteMeasurePermission,
            $viewMeasurePermission
        ]);


        // Assign user permissions to roles
        $adminRole->givePermissionTo([
            $createUserPermission,
            $editUserPermission,
            $deleteUserPermission
        ]);
    }
}
