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

    protected static ?string $title = 'الآيات';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number_in_surah')
                    ->label('رقم الآية')
                    ->sortable(),
                Tables\Columns\TextColumn::make('text_uthmani')
                    ->label('النص العثماني')
                    ->limit(80)
                    ->wrap(),
                Tables\Columns\TextColumn::make('number_in_quran')
                    ->label('رقمها في القرآن')
                    ->sortable(),
            ])
            ->defaultSort('number_in_surah')
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('number_in_surah')
                    ->label('رقم الآية'),
                Infolists\Components\TextEntry::make('text_uthmani')
                    ->label('النص العثماني')
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('number_in_quran')
                    ->label('رقمها في القرآن'),
            ]);
    }
}
