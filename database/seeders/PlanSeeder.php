<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'title' => 'Basic Plan',
            'cost' => 99.99,
            'description' => '## Perfect to start

This plan includes everything you need to start your onboarding:

- Initial consultation
- Basic documentation
- Email support
- Access to common resources',
            'includes' => ['Initial consultation', 'Basic documentation', 'Email support', 'Resource access'],
            'active' => true,
            'order' => 1,
        ]);

        Plan::create([
            'title' => 'Professional Plan',
            'cost' => 299.99,
            'description' => '## The most popular option

Ideal for growing companies with more complex needs:

- Complete consultation
- Personalized documentation
- Phone support
- Weekly follow-up meetings
- Access to all premium tools',
            'includes' => ['Complete consultation', 'Personalized documentation', 'Phone support', 'Weekly meetings', 'Premium tools'],
            'active' => true,
            'order' => 2,
        ]);

        Plan::create([
            'title' => 'Enterprise Plan',
            'cost' => 799.99,
            'description' => '## Complete solution

For companies with special requirements:

- 24/7 dedicated consultation
- Fully personalized documentation
- Dedicated support team
- Daily meetings
- Custom integrations
- Guaranteed SLA',
            'includes' => ['24/7 consultation', 'Personalized documentation', 'Dedicated team', 'Daily meetings', 'Custom integrations', 'SLA guaranteed'],
            'active' => true,
            'order' => 3,
        ]);
    }
}
