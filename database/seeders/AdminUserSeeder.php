<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@smartquran.com'],
            [
                'name' => 'المدير العام',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole);
        }

        if (!$admin->profile) {
            $admin->profile()->create([
                'bio' => 'مدير منصة الحفظ الذكي للقرآن الكريم',
                'country' => 'السعودية',
                'timezone' => 'Asia/Riyadh',
            ]);
        }
    }
}
