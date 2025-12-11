<?php

namespace App\Filament\Resources\Smtps\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SmtpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->searchable()
                    ->preload()
                    ->relationship('user', 'name'),
                TextInput::make('host')
                    ->required(),
                TextInput::make('port')
                    ->required()
                    ->numeric(),
                TextInput::make('username')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('encryption')
                    ->searchable()
                    ->options([
                        'tls' => 'TLS',
                        'ssl' => 'SSL',
                        null => 'None'
                    ]),
                TextInput::make('from_email')
                    ->email()
                    ->required(),
                TextInput::make('from_name')
                    ->required(),
            ]);
    }
}
