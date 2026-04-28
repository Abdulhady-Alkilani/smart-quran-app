<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $studentRole = Role::where('name', 'student')->first();

        $students = [
            ['name' => 'أحمد محمد', 'email' => 'ahmed@example.com', 'country' => 'مصر'],
            ['name' => 'فاطمة علي', 'email' => 'fatima@example.com', 'country' => 'الأردن'],
            ['name' => 'عمر خالد', 'email' => 'omar@example.com', 'country' => 'السعودية'],
            ['name' => 'مريم حسن', 'email' => 'maryam@example.com', 'country' => 'الكويت'],
            ['name' => 'يوسف إبراهيم', 'email' => 'yousuf@example.com', 'country' => 'الإمارات'],
        ];

        foreach ($students as $studentData) {
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if ($studentRole && !$user->roles()->where('role_id', $studentRole->id)->exists()) {
                $user->roles()->attach($studentRole);
            }

            if (!$user->profile) {
                $user->profile()->create([
                    'bio' => 'طالب في منصة الحفظ الذكي',
                    'country' => $studentData['country'],
                    'timezone' => 'Asia/Riyadh',
                ]);
            }
        }
    }
}
