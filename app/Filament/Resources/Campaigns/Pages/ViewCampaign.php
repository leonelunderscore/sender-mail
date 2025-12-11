<?php

namespace App\Filament\Resources\Campaigns\Pages;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Imports\RecipientImport;
use App\Models\Campaign;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ViewRecord;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ViewCampaign extends ViewRecord
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->icon('heroicon-o-pencil-square'),
            Action::make('send')
                ->color(fn(Campaign $record) => $record->is_active ? 'danger' : 'success')
                ->icon(fn(Campaign $record) => $record->is_active ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                ->label(fn(Campaign $record) => $record->is_active ? 'Pause' : 'Send')
                ->action(function (Campaign $record) {
                    $record->update(['is_active' => !$record->is_active]);
                })
                ->requiresConfirmation(),
            Action::make('upload')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('info')
                ->label('Recipients')
                ->slideOver()
                ->action(function (Campaign $record, array $data) {
                    $files = $data['files'];
                    foreach ($files as $file) {
                        Excel::import(new RecipientImport($record), storage_path('app/public/' . $file));
                    }
                })
                ->schema([
                    FileUpload::make('files')
                        ->disk('public')
                        ->multiple()
                        ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file) => str(md5(uniqid()) . "." . $file->extension())->toString())
                        ->required()
                        ->directory('files')
                        ->label('Import file')
                ])
        ];
    }
}
