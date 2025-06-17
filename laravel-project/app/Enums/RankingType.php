<?php

namespace App\Enums;

use Illuminate\Support\Carbon;

/**
 * ランキング集計タイプ
 */
enum RankingType: string
{
 
    /**
     * 日
     */
    case DAILY = 'dayly';
    /**
     * 週
     */
    case WEEKLY = 'weekly';
    /**
     * 月
     */
    case MONTHLY = 'monthly';
    /**
     * 年
     */
    case YEARLY = 'yearly';

    public function label(): string
    {
        return match ($this) {
            self::DAILY  => '日間',
            self::WEEKLY => '週間',
            self::MONTHLY => '月間',
            self::YEARLY => '年間',
        };
    }

    /**
     * ラベルとバリューの配列を返却する
     * @return \Illuminate\Support\Collection<mixed, array{label: string, value: string>}
     */
    public static function getLabelsValues(): array 
    {
        return collect(self::cases())->map(fn ($type) => [
            'label' => $type->label(),
            'value' => $type->value,
        ])->toArray();
    }


    /**
     * 起点となる日から開始日時、終了日時を作成して返却
     * @param \Illuminate\Support\Carbon $baseDate
     * @return Carbon[]
     */
    public function getDateRange(Carbon $baseDate): array
    {
        return match ($this) {
            self::DAILY => [$baseDate->copy()->startOfDay(), $baseDate->copy()->endOfDay()],
            self::WEEKLY => [$baseDate->copy()->startOfWeek(), $baseDate->copy()->endOfWeek()],
            self::MONTHLY => [$baseDate->copy()->startOfMonth(), $baseDate->copy()->endOfMonth()],
            self::YEARLY => [$baseDate->copy()->startOfYear(), $baseDate->copy()->endOfYear()],
        };
    }
}
