<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
class SuperAdminSeeder extends Seeder
{
    public function run()
    {
         $role = Role::where('name', 'superadmin')->first();
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Change password later
            'tenant_id' => null, // SuperAdmin doesn't belong to any tenant
             'role_id' => $role->id,
        ]);

        // Attach superadmin role (assuming roles relationship is belongsToMany)
        $role = Role::where('name', 'superadmin')->first();
        $user->role()->associate($role); // or ->roles()->attach($role->id) if many-to-many
        $user->save();
    }
}
