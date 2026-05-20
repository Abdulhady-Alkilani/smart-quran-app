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

    protected static ?string $title = 'Memorization Progress';

    protected static ?string $recordTitleAttribute = 'status';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label(__('filament.status'))
                    ->options([
                        'learning' => __('filament.learning'),
                        'memorized' => __('filament.memorized'),
                    ])
                    ->required(),
                Forms\Components\TextInput::make('repetition_count')
                    ->label(__('filament.repetition_count'))
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('easiness_factor')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('interval_days')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('last_review_date')
                    ->label(__('filament.last_review')),
                Forms\Components\DatePicker::make('next_review_date')
                    ->label(__('filament.next_review_date')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ayah.surah.name_ar')
                    ->label(__('filament.surah_name')),
                Tables\Columns\TextColumn::make('ayah.number_in_surah')
                    ->label(__('filament.ayah_number_in_surah')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'memorized' => 'success',
                        'learning' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'memorized' => __('filament.memorized'),
                        'learning' => __('filament.learning'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('repetition_count')
                    ->label(__('filament.repetition_count')),
                Tables\Columns\TextColumn::make('next_review_date')
                    ->label(__('filament.next_review_date'))
                    ->date('Y-m-d'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.status'))
                    ->options([
                        'learning' => __('filament.learning'),
                        'memorized' => __('filament.memorized'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(__('filament.view')),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('ayah.surah.name_ar')
                    ->label(__('filament.surah_name')),
                Infolists\Components\TextEntry::make('ayah.number_in_surah')
                    ->label(__('filament.ayah_number_in_surah')),
                Infolists\Components\TextEntry::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'memorized' => 'success',
                        'learning' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'memorized' => __('filament.memorized'),
                        'learning' => __('filament.learning'),
                        default => $state,
                    }),
                Infolists\Components\TextEntry::make('repetition_count')
                    ->label(__('filament.repetition_count')),
                Infolists\Components\TextEntry::make('next_review_date')
                    ->label(__('filament.next_review_date'))
                    ->date('Y-m-d'),
            ]);
    }
}
