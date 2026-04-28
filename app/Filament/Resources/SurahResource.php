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

    protected static ?string $navigationLabel = 'سور القرآن';

    protected static ?string $modelLabel = 'سورة';

    protected static ?string $pluralModelLabel = 'سور القرآن';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label('رقم السورة')
                    ->numeric()
                    ->disabled(),
                Forms\Components\TextInput::make('name_ar')
                    ->label('الاسم بالعربية')
                    ->disabled(),
                Forms\Components\TextInput::make('name_en')
                    ->label('الاسم بالإنجليزية')
                    ->disabled(),
                Forms\Components\TextInput::make('revelation_type')
                    ->label('نوع الوحي')
                    ->disabled(),
                Forms\Components\TextInput::make('total_ayahs')
                    ->label('عدد الآيات')
                    ->numeric()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('الرقم')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label('الاسم بالعربية')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label('الاسم بالإنجليزية')
                    ->searchable(),
                Tables\Columns\TextColumn::make('revelation_type')
                    ->label('نوع الوحي')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Meccan' => 'warning',
                        'Medinan' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Meccan' => 'مكية',
                        'Medinan' => 'مدنية',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('total_ayahs')
                    ->label('عدد الآيات')
                    ->badge(),
                Tables\Columns\TextColumn::make('ayahs_count')
                    ->label('آيات محفوظة في DB')
                    ->counts('ayahs')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('revelation_type')
                    ->label('نوع الوحي')
                    ->options([
                        'Meccan' => 'مكية',
                        'Medinan' => 'مدنية',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('number')
                    ->label('رقم السورة'),
                Infolists\Components\TextEntry::make('name_ar')
                    ->label('الاسم بالعربية'),
                Infolists\Components\TextEntry::make('name_en')
                    ->label('الاسم بالإنجليزية'),
                Infolists\Components\TextEntry::make('revelation_type')
                    ->label('نوع الوحي')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Meccan' => 'warning',
                        'Medinan' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Meccan' => 'مكية',
                        'Medinan' => 'مدنية',
                        default => $state,
                    }),
                Infolists\Components\TextEntry::make('total_ayahs')
                    ->label('عدد الآيات'),
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
