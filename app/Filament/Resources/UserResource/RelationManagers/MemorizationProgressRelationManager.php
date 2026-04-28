<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms;
use Filament\Forms\Form;

class MemorizationProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'memorizationProgress';

    protected static ?string $title = 'تقدم الحفظ';

    protected static ?string $recordTitleAttribute = 'status';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'learning' => 'قيد التعلم',
                        'memorized' => 'محفوظة',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('repetition_count')
                    ->label('مرات التكرار')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('easiness_factor')
                    ->label('عامل السهولة')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('interval_days')
                    ->label('أيام الفاصل الزمني')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('last_review_date')
                    ->label('تاريخ آخر مراجعة'),
                Forms\Components\DatePicker::make('next_review_date')
                    ->label('المراجعة القادمة'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ayah.surah.name_ar')
                    ->label('السورة'),
                Tables\Columns\TextColumn::make('ayah.number_in_surah')
                    ->label('رقم الآية'),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'memorized' => 'success',
                        'learning' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'memorized' => 'محفوظة',
                        'learning' => 'قيد التعلم',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('repetition_count')
                    ->label('مرات التكرار'),
                Tables\Columns\TextColumn::make('next_review_date')
                    ->label('المراجعة القادمة')
                    ->date('Y-m-d'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'learning' => 'قيد التعلم',
                        'memorized' => 'محفوظة',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('ayah.surah.name_ar')
                    ->label('السورة'),
                Infolists\Components\TextEntry::make('ayah.number_in_surah')
                    ->label('رقم الآية'),
                Infolists\Components\TextEntry::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'memorized' => 'success',
                        'learning' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'memorized' => 'محفوظة',
                        'learning' => 'قيد التعلم',
                        default => $state,
                    }),
                Infolists\Components\TextEntry::make('repetition_count')
                    ->label('مرات التكرار'),
                Infolists\Components\TextEntry::make('next_review_date')
                    ->label('المراجعة القادمة')
                    ->date('Y-m-d'),
            ]);
    }
}
