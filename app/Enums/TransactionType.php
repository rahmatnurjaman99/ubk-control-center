<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TransactionType: string implements HasLabel, HasColor
{
    case Income = 'income';
    case Expense = 'expense';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Income => __('filament.transaction_types.income'),
            self::Expense => __('filament.transaction_types.expense'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Income => 'success',
            self::Expense => 'danger',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type): array => [$type->value => $type->getLabel() ?? $type->value])
            ->all();
    }
}
