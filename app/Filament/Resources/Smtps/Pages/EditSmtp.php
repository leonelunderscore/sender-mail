<?php

namespace App\Filament\Resources\Smtps\Pages;

use App\Filament\Resources\Smtps\SmtpResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSmtp extends EditRecord
{
    protected static string $resource = SmtpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
