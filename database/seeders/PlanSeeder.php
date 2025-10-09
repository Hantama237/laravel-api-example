<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            // B2C Plans (Individual consumers)
            [
                'name' => 'Basic Practice',
                'type' => 'B2C',
                'slot' => 5,
                'duration' => '7 days',
                'base_price' => 9.99,
                'description' => 'Perfect for individuals who want to practice for their driving exam. Get access to 5 exam slots for 7 days.',
                'is_enable' => true,
                'is_built_in' => false,
            ],
            [
                'name' => 'Standard Learner',
                'type' => 'B2C',
                'slot' => 15,
                'duration' => '30 days',
                'base_price' => 24.99,
                'description' => 'Most popular plan for individual learners. 15 exam attempts over 30 days to thoroughly prepare for your driving test.',
                'is_enable' => true,
                'is_built_in' => false,
            ],
            [
                'name' => 'Premium Intensive',
                'type' => 'B2C',
                'slot' => 50,
                'duration' => '90 days',
                'base_price' => 59.99,
                'description' => 'Comprehensive preparation package with 50 exam slots over 3 months. Ideal for serious learners.',
                'is_enable' => true,
                'is_built_in' => false,
            ],
            [
                'name' => 'Trial Version',
                'type' => 'B2C',
                'slot' => 2,
                'duration' => '3 days',
                'base_price' => 0.00,
                'description' => 'Free trial to experience our exam platform. Limited to 2 attempts over 3 days.',
                'is_enable' => true,
                'is_built_in' => true,
            ],

            // B2B Plans (Business/Organizational)
            [
                'name' => 'Driving School Basic',
                'type' => 'B2B',
                'slot' => 10,
                'duration' => '180 days',
                'base_price' => 199.99,
                'description' => 'Designed for small driving schools. 100 exam slots for 6 months to serve multiple students.',
                'is_enable' => true,
                'is_built_in' => false,
            ],
            [
                'name' => 'Driving School Professional',
                'type' => 'B2B',
                'slot' => 23,
                'duration' => '365 days',
                'base_price' => 799.99,
                'description' => 'Professional package for established driving schools. 500 exam slots for 1 year with priority support.',
                'is_enable' => true,
                'is_built_in' => false,
            ],
            [
                'name' => 'Corporate Fleet Training',
                'type' => 'B2B',
                'slot' => 22,
                'duration' => '365 days',
                'base_price' => 1299.99,
                'description' => 'Enterprise solution for companies training their fleet drivers. 1000 exam slots for 1 year.',
                'is_enable' => true,
                'is_built_in' => false,
            ],
            [
                'name' => 'Educational Institution',
                'type' => 'B2B',
                'slot' => 22,
                'duration' => '365 days',
                'base_price' => 1999.99,
                'description' => 'Comprehensive package for universities and educational institutions. 2000 exam slots for 1 year.',
                'is_enable' => true,
                'is_built_in' => false,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }

        $this->command->info('Created ' . count($plans) . ' plans successfully!');
    }
}
