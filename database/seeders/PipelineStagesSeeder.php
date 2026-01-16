<?php

namespace Database\Seeders;

use App\Models\PipelineStage;
use Illuminate\Database\Seeder;

class PipelineStagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            [
                'name' => 'Lead',
                'order' => 1,
                'color' => '#9CA3AF',
                'description' => 'Initial contact with potential customer',
                'is_active' => true,
            ],
            [
                'name' => 'Qualified',
                'order' => 2,
                'color' => '#3B82F6',
                'description' => 'Lead has been qualified as a potential customer',
                'is_active' => true,
            ],
            [
                'name' => 'Proposal',
                'order' => 3,
                'color' => '#8B5CF6',
                'description' => 'Proposal or quote has been sent',
                'is_active' => true,
            ],
            [
                'name' => 'Negotiation',
                'order' => 4,
                'color' => '#F59E0B',
                'description' => 'In negotiation phase',
                'is_active' => true,
            ],
            [
                'name' => 'Closed Won',
                'order' => 5,
                'color' => '#10B981',
                'description' => 'Deal successfully closed',
                'is_active' => true,
            ],
            [
                'name' => 'Closed Lost',
                'order' => 6,
                'color' => '#EF4444',
                'description' => 'Deal was lost',
                'is_active' => true,
            ],
        ];

        foreach ($stages as $stage) {
            PipelineStage::create($stage);
        }
    }
}
