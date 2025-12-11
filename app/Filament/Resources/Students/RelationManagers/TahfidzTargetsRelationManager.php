<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\RelationManagers;

use App\Filament\Resources\TahfidzTargets\Schemas\TahfidzTargetForm;
use App\Filament\Resources\TahfidzTargets\Tables\TahfidzTargetsTable;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TahfidzTargetsRelationManager extends RelationManager
{
    protected static string $relationship = 'tahfidzTargets';

    public function form(Schema $schema): Schema
    {
        return TahfidzTargetForm::configure($schema, true);
    }

    public function table(Table $table): Table
    {
        return TahfidzTargetsTable::configure($table, true)
            ->headerActions([
                CreateAction::make()
                    ->label(__('filament.tahfidz_targets.actions.assign'))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['student_id'] = $this->getOwnerRecord()->getKey();

                        return $data;
                    }),
            ]);
    }
}
