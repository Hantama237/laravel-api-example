<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get plans
        $partnerPlan = Plan::where('type', 'B2B')->first();
        $studentPlan = Plan::where('type', 'B2C')->first();

        // Create Super Admin
        $superAdmin = User::create([
            'id' => Str::uuid(),
            'email' => 'superadmin@rijbewijsguru.nl',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');

        // Create Admin
        $admin = User::create([
            'id' => Str::uuid(),
            'email' => 'admin@rijbewijsguru.nl',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create Partner with B2B subscription
        if ($partnerPlan) {
            $partner = User::create([
                'id' => Str::uuid(),
                'email' => 'partner@rijbewijsguru.nl',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $partner->assignRole('partner');

            // Create subscription for partner
            Subscription::create([
                'id' => Str::uuid(),
                'user_id' => $partner->id,
                'plan_id' => $partnerPlan->id,
                'total_slot' => $partnerPlan->slot,
                'status' => 'ACTIVE',
                'start_date' => now(),
                'end_date' => now()->addMonths($partnerPlan->duration_in_months),
            ]);
        }

        // Create Student with B2C subscription
        if ($studentPlan) {
            $student = User::create([
                'id' => Str::uuid(),
                'email' => 'student@rijbewijsguru.nl',
                'pin' => 'STU123',
                'email_verified_at' => now(),
            ]);
            $student->assignRole('student');

            // Create subscription for student
            Subscription::create([
                'id' => Str::uuid(),
                'user_id' => $student->id,
                'plan_id' => $studentPlan->id,
                'total_slot' => $studentPlan->slot,
                'status' => 'ACTIVE',
                'start_date' => now(),
                'end_date' => now()->addMonths($studentPlan->duration_in_months),
            ]);
        }
    }
}
