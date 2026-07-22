<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $modules = [
            'dashboard', 'companies', 'branches', 'hotels', 'rooms', 'room-types',
            'bookings', 'check-ins', 'check-outs', 'guests', 'crm', 'leads',
            'housekeeping', 'maintenance', 'restaurant', 'room-service', 'laundry',
            'spa', 'gym', 'pool', 'events', 'inventory', 'purchases', 'suppliers',
            'finance', 'employees', 'departments', 'designations', 'attendance',
            'leaves', 'payroll', 'shifts', 'tasks', 'documents',
            'communications', 'cms', 'marketing', 'reports', 'analytics',
            'settings', 'security', 'audit', 'notifications', 'export', 'import',
            'backup',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action} {$module}", 'guard_name' => 'web']);
            }
        }

        $permission = Permission::firstOrCreate(['name' => 'approve expenses', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'approve leaves', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'process payroll', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage roles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage permissions', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view logs', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'export data', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'import data', 'guard_name' => 'web']);

        $roles = [
            'super-admin' => [],
            'admin' => $modules,
            'manager' => ['dashboard', 'bookings', 'check-ins', 'check-outs', 'guests', 'crm', 'leads', 'housekeeping', 'maintenance', 'restaurant', 'room-service', 'inventory', 'purchases', 'suppliers', 'reports', 'analytics', 'tasks'],
            'receptionist' => ['dashboard', 'bookings', 'check-ins', 'check-outs', 'guests', 'crm', 'rooms', 'events'],
            'housekeeping' => ['dashboard', 'housekeeping', 'maintenance', 'rooms'],
            'maintenance' => ['dashboard', 'maintenance', 'rooms'],
            'restaurant' => ['dashboard', 'restaurant', 'room-service', 'inventory'],
            'accountant' => ['dashboard', 'finance', 'reports', 'analytics', 'inventory', 'purchases', 'suppliers'],
            'hr-manager' => ['dashboard', 'employees', 'departments', 'designations', 'attendance', 'leaves', 'payroll', 'shifts'],
            'front-desk' => ['dashboard', 'bookings', 'check-ins', 'check-outs', 'guests', 'rooms'],
        ];

        foreach ($roles as $roleName => $moduleAccess) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            if ($roleName === 'super-admin') {
                $role->syncPermissions(Permission::all());
            } else {
                $permissions = collect($moduleAccess)->flatMap(function ($module) use ($actions) {
                    return array_map(fn ($action) => "{$action} {$module}", $actions);
                })->toArray();

                if ($roleName === 'accountant') {
                    $permissions[] = 'approve expenses';
                }
                if ($roleName === 'hr-manager') {
                    $permissions[] = 'approve leaves';
                    $permissions[] = 'process payroll';
                }

                $role->syncPermissions(Permission::whereIn('name', $permissions)->get());
            }
        }
    }
}
