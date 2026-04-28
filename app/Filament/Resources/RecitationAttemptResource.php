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

    protected static ?string $navigationLabel = 'محاولات التسميع';

    protected static ?string $modelLabel = 'محاولة تسميع';

    protected static ?string $pluralModelLabel = 'محاولات التسميع';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user.name')
                    ->label('الطالب')
                    ->disabled(),
                TextInput::make('ayah.surah.name_ar')
                    ->label('السورة')
                    ->disabled(),
                TextInput::make('similarity_score')
                    ->label('نسبة التطابق')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('الطالب')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ayah.surah.name_ar')
                    ->label('السورة'),
                Tables\Columns\TextColumn::make('ayah.number_in_surah')
                    ->label('رقم الآية'),
                Tables\Columns\TextColumn::make('similarity_score')
                    ->label('نسبة التطابق')
                    ->suffix('%')
                    ->color(fn (float $state): string => $state >= 90 ? 'success' : ($state >= 70 ? 'warning' : 'danger')),
                Tables\Columns\IconColumn::make('is_passed')
                    ->label('ناجح')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('mistakes_count')
                    ->label('عدد الأخطاء')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                Tables\Columns\ViewColumn::make('audio_file_path')
                    ->label('الاستماع')
                    ->view('filament.columns.audio-player'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_passed')
                    ->label('حالة النجاح')
                    ->placeholder('الكل')
                    ->trueLabel('ناجح فقط')
                    ->falseLabel('راسب فقط'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name')
                    ->label('الطالب'),
                Infolists\Components\TextEntry::make('ayah.surah.name_ar')
                    ->label('السورة'),
                Infolists\Components\TextEntry::make('similarity_score')
                    ->label('نسبة التطابق')
                    ->suffix('%'),
                Infolists\Components\IconEntry::make('is_passed')
                    ->label('ناجح')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Infolists\Components\TextEntry::make('mistakes_count')
                    ->label('عدد الأخطاء')
                    ->badge(),
                Infolists\Components\TextEntry::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d H:i'),
                Infolists\Components\TextEntry::make('transcribed_text')
                    ->label('نص التلاوة المتعرف عليه')
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
