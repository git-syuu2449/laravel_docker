<?php

namespace Database\Factories;

use App\Enums\RankingType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ranking>
 */
class RankingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => RankingType::DAILY,
            'base_date' => today(),
            'start_at' => now()->startOfDay(),
            'end_at' => now()->endOfDay(),
            'count' => 0,
            'total' => 0,
            'average' => 0,
        ];
    }
}
