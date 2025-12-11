<?php

namespace App\Filament\Resources\Smtps;

use App\Filament\Resources\Smtps\Pages\CreateSmtp;
use App\Filament\Resources\Smtps\Pages\EditSmtp;
use App\Filament\Resources\Smtps\Pages\ListSmtps;
use App\Filament\Resources\Smtps\Schemas\SmtpForm;
use App\Filament\Resources\Smtps\Tables\SmtpsTable;
use App\Models\Smtp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SmtpResource extends Resource
{
    protected static ?string $model = Smtp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;
    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::PaperAirplane;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return SmtpForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SmtpsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSmtps::route('/'),
            'create' => CreateSmtp::route('/create'),
            'edit' => EditSmtp::route('/{record}/edit'),
        ];
    }
}
