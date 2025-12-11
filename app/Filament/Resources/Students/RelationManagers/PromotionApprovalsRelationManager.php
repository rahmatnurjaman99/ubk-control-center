<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\RelationManagers;

use App\Enums\GradeLevel;
use App\Enums\PromotionApprovalStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PromotionApprovalsRelationManager extends RelationManager
{
    protected static string $relationship = 'promotionApprovals';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('filament.promotion_approvals.heading'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('targetAcademicYear.name')
                    ->label(__('filament.promotion_approvals.fields.target_academic_year'))
                    ->sortable(),
                TextColumn::make('target_grade_level')
                    ->label(__('filament.promotion_approvals.fields.target_grade_level'))
                    ->formatStateUsing(fn (?string $state): ?string => blank($state) ? null : GradeLevel::tryFrom($state)?->label())
                    ->badge(),
                TextColumn::make('targetClassroom.name')
                    ->label(__('filament.promotion_approvals.fields.target_classroom'))
                    ->placeholder('-'),
                TextColumn::make('outstanding_amount')
                    ->label(__('filament.promotion_approvals.fields.outstanding_amount'))
                    ->money('IDR')
                    ->alignRight(),
                TextColumn::make('status')
                    ->label(__('filament.promotion_approvals.fields.status'))
                    ->formatStateUsing(fn (PromotionApprovalStatus|string|null $state): ?string => match (true) {
                        $state instanceof PromotionApprovalStatus => $state->getLabel(),
                        blank($state) => null,
                        default => PromotionApprovalStatus::from((string) $state)->getLabel(),
                    })
                    ->badge()
                    ->color(fn (PromotionApprovalStatus|string|null $state): ?string => match (true) {
                        $state instanceof PromotionApprovalStatus => $state->getColor(),
                        blank($state) => null,
                        default => PromotionApprovalStatus::from((string) $state)->getColor(),
                    }),
                TextColumn::make('requester.name')
                    ->label(__('filament.promotion_approvals.fields.requested_by'))
                    ->placeholder('-'),
                TextColumn::make('approver.name')
                    ->label(__('filament.promotion_approvals.fields.approved_by'))
                    ->placeholder('-'),
                TextColumn::make('approved_at')
                    ->label(__('filament.promotion_approvals.fields.approved_at'))
                    ->dateTime()
                    ->placeholder('-'),
            ])
            ->recordActions([])
            ->headerActions([]);
    }
}
