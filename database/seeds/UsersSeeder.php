<?php

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\RoleHierarchy;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Create roles */
        $adminRole = Role::create(['name' => 'admin']);
        RoleHierarchy::create([
            'role_id' => $adminRole->id,
            'hierarchy' => 1,
        ]);
        $userRole = Role::create(['name' => 'user']);
        RoleHierarchy::create([
            'role_id' => $userRole->id,
            'hierarchy' => 2,
        ]);
        $guestRole = Role::create(['name' => 'guest']);
        RoleHierarchy::create([
            'role_id' => $guestRole->id,
            'hierarchy' => 3,
        ]);

        $testSubjectRole = Role::create(['name' => 'test-subject']);
        RoleHierarchy::create([
            'role_id' => $testSubjectRole->id,
            'hierarchy' => 4,
        ]);

        /*  insert users   */
        $user = User::create([
            'first_name' => 'admin',
            'last_name' => '',
            'middle_name' => '',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'user,admin',
            'status' => 'Active',
        ]);
        $user->assignRole('user');
        $user->assignRole('admin');
    }
}
