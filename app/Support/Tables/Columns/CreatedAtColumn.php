<?php

declare(strict_types=1);

namespace App\Support\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class CreatedAtColumn
{
    public static function make(string $column = 'created_at'): TextColumn
    {
        return TextColumn::make($column)
            ->label(__('filament.common.created_at'))
            ->since()
            ->dateTimeTooltip()
            ->sortable()
            ->toggleable();
    }
}
