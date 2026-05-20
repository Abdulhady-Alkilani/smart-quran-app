<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurahResource\Pages;
use App\Filament\Resources\SurahResource\RelationManagers\AyahsRelationManager;
use App\Models\Surah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class SurahResource extends Resource
{
    protected static ?string $model = Surah::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getNavigationLabel(): string
    {
        return __('filament.surahs');
    }

    public static function getModelLabel(): string
    {
        return __('filament.surah');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.surahs');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label(__('filament.number'))
                    ->numeric()
                    ->disabled(),
                Forms\Components\TextInput::make('name_ar')
                    ->label(__('filament.name_ar'))
                    ->disabled(),
                Forms\Components\TextInput::make('name_en')
                    ->label(__('filament.name_en'))
                    ->disabled(),
                Forms\Components\TextInput::make('revelation_type')
                    ->label(__('filament.revelation_type'))
                    ->disabled(),
                Forms\Components\TextInput::make('total_ayahs')
                    ->label(__('filament.total_ayahs'))
                    ->numeric()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label(__('filament.number'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('filament.name_ar'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('filament.name_en'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('revelation_type')
                    ->label(__('filament.revelation_type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Meccan' => 'warning',
                        'Medinan' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Meccan' => __('filament.meccan'),
                        'Medinan' => __('filament.medinan'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('total_ayahs')
                    ->label(__('filament.total_ayahs'))
                    ->badge(),
                Tables\Columns\TextColumn::make('ayahs_count')
                    ->label(__('filament.ayahs'))
                    ->counts('ayahs')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('revelation_type')
                    ->label(__('filament.revelation_type'))
                    ->options([
                        'Meccan' => __('filament.meccan'),
                        'Medinan' => __('filament.medinan'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(__('filament.view')),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('number')
                    ->label(__('filament.number')),
                Infolists\Components\TextEntry::make('name_ar')
                    ->label(__('filament.name_ar')),
                Infolists\Components\TextEntry::make('name_en')
                    ->label(__('filament.name_en')),
                Infolists\Components\TextEntry::make('revelation_type')
                    ->label(__('filament.revelation_type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Meccan' => 'warning',
                        'Medinan' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Meccan' => __('filament.meccan'),
                        'Medinan' => __('filament.medinan'),
                        default => $state,
                    }),
                Infolists\Components\TextEntry::make('total_ayahs')
                    ->label(__('filament.total_ayahs')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AyahsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurahs::route('/'),
            'view' => Pages\ViewSurah::route('/{record}'),
        ];
    }
}
