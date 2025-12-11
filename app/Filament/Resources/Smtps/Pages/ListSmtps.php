<?php

namespace App\Filament\Resources\Smtps\Pages;

use App\Filament\Resources\Smtps\SmtpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSmtps extends ListRecords
{
    protected static string $resource = SmtpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
