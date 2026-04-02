<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Define All Powers
        $powers = [
            'access-admin'     => 'Access Admin Dashboard',
            'manage-users'     => 'Edit All Users & Roles',
            'task-assign'      => 'Assign Tasks to Others',
            'task-view-all'    => 'View Everyone\'s Tasks',
            'task-edit-all'    => 'Edit Anyone\'s Tasks',
            'task-delete'      => 'Delete Tasks',
            'view-tasks'       => 'View Personal Tasks',
            'complete-tasks'   => 'Mark Tasks Finished',
        ];

        $p = [];
        foreach ($powers as $slug => $name) {
            $p[$slug] = Permission::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        // 2. Define the 4 Ranks
        $superAdmin  = Role::firstOrCreate(['slug' => 'superadmin'], ['name' => 'Super Admin']);
        $taskManager = Role::firstOrCreate(['slug' => 'task-manager'], ['name' => 'Task Manager']);
        $admin       = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrator']);
        $student     = Role::firstOrCreate(['slug' => 'student'], ['name' => 'Student']);

        // 3. Link Powers to Ranks
        $superAdmin->permissions()->sync(Permission::all()->pluck('id'));

        $taskManager->permissions()->sync([
            $p['access-admin']->id,
            $p['task-assign']->id,
            $p['task-view-all']->id,
            $p['task-edit-all']->id
        ]);

        $admin->permissions()->sync([
            $p['access-admin']->id,
            $p['task-assign']->id,
            $p['task-view-all']->id,
            $p['task-edit-all']->id,
            $p['task-delete']->id
        ]);

        $student->permissions()->sync([
            $p['view-tasks']->id,
            $p['complete-tasks']->id
        ]);

        // 4. Assign YOU as Super Admin
        $me = User::where('email', 'diamond@gmail.com')->first();
        if ($me) {
            $me->roles()->sync([$superAdmin->id]);
        }
    }
}