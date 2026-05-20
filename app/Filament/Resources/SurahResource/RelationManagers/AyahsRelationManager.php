<?php

namespace App\Filament\Resources\SurahResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class AyahsRelationManager extends RelationManager
{
    protected static string $relationship = 'ayahs';

    protected static ?string $title = 'Ayahs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number_in_surah')
                    ->label(__('filament.ayah_number_in_surah'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('text_uthmani')
                    ->label(__('filament.text_uthmani'))
                    ->limit(80)
                    ->wrap(),
                Tables\Columns\TextColumn::make('number_in_quran')
                    ->label(__('filament.number_in_quran'))
                    ->sortable(),
            ])
            ->defaultSort('number_in_surah')
            ->actions([
                Tables\Actions\ViewAction::make()->label(__('filament.view')),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('number_in_surah')
                    ->label(__('filament.ayah_number_in_surah')),
                Infolists\Components\TextEntry::make('text_uthmani')
                    ->label(__('filament.text_uthmani'))
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('number_in_quran')
                    ->label(__('filament.number_in_quran')),
            ]);
    }
}
