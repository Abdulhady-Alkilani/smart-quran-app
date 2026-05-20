<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecitationAttemptResource\Pages;
use App\Models\RecitationAttempt;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class RecitationAttemptResource extends Resource
{
    protected static ?string $model = RecitationAttempt::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    public static function getNavigationLabel(): string
    {
        return __('filament.recitation_attempts');
    }

    public static function getModelLabel(): string
    {
        return __('filament.recitation_attempt');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.recitation_attempts');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user.name')
                    ->label(__('filament.user_name'))
                    ->disabled(),
                TextInput::make('ayah.surah.name_ar')
                    ->label(__('filament.surah_name'))
                    ->disabled(),
                TextInput::make('similarity_score')
                    ->label(__('filament.similarity_score'))
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament.user_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('ayah.surah.name_ar')
                    ->label(__('filament.surah_name')),
                Tables\Columns\TextColumn::make('ayah.number_in_surah')
                    ->label(__('filament.ayah_number_in_surah')),
                Tables\Columns\TextColumn::make('similarity_score')
                    ->label(__('filament.similarity_score'))
                    ->suffix('%')
                    ->color(fn (float $state): string => $state >= 90 ? 'success' : ($state >= 70 ? 'warning' : 'danger')),
                Tables\Columns\IconColumn::make('is_passed')
                    ->label(__('filament.is_passed'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('mistakes_count')
                    ->label(__('filament.mistakes_count'))
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                Tables\Columns\ViewColumn::make('audio_file_path')
                    ->label(__('filament.audio_file'))
                    ->view('filament.columns.audio-player'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_passed')
                    ->label(__('filament.status'))
                    ->placeholder(__('filament.all'))
                    ->trueLabel(__('filament.passed'))
                    ->falseLabel(__('filament.failed')),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()->label(__('filament.view')),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name')
                    ->label(__('filament.user_name')),
                Infolists\Components\TextEntry::make('ayah.surah.name_ar')
                    ->label(__('filament.surah_name')),
                Infolists\Components\TextEntry::make('similarity_score')
                    ->label(__('filament.similarity_score'))
                    ->suffix('%'),
                Infolists\Components\IconEntry::make('is_passed')
                    ->label(__('filament.is_passed'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Infolists\Components\TextEntry::make('mistakes_count')
                    ->label(__('filament.mistakes_count'))
                    ->badge(),
                Infolists\Components\TextEntry::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime('Y-m-d H:i'),
                Infolists\Components\TextEntry::make('transcribed_text')
                    ->label(__('filament.transcribed_text'))
                    ->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecitationAttempts::route('/'),
            'view' => Pages\ViewRecitationAttempt::route('/{record}'),
        ];
    }
}
