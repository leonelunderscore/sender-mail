<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use App\Models\Smtp;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn(Set $set) => $set('smtp_id', null))
                    ->relationship('user', 'name'),
                Select::make('smtp_id')
                    ->label('SMTP')
                    ->searchable()
                    ->live()
                    ->preload()
                    ->options(
                        function (Get $get) {
                            return Smtp::when(!auth()->user()->is_admin, fn($query) => $query->where('user_id', $get('user_id')))
                                ->when(auth()->user()->is_admin && $get('user_id'), fn($query) => $query->where('user_id', $get('user_id')))
                                ->selectRaw("concat(reference, ' - ', from_email) as label, id")
                                ->pluck('label', 'id')
                                ->toArray();
                        }
                    ),
                TextInput::make('name')
                    ->required(),
                TextInput::make('subject')
                    ->required(),
                RichEditor::make('html')->label('Message')->toolbarButtons([
                    ['bold', 'italic', 'small', 'underline', 'strike', 'subscript', 'superscript', 'link', 'code'],
                    ['h1', 'h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                    ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                    ['table', 'attachFiles'],
                    ['undo', 'redo'],
                ])
                    ->fileAttachmentsDisk('s3')
                    ->fileAttachmentsDirectory('campaigns/attachments')
                    ->fileAttachmentsVisibility('public')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('attachments')
                    ->disk('s3')
                    ->multiple()
                    ->visibility('public')
                    ->columnSpanFull()
                    ->directory('attachments')
                    ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file) => str(md5(uniqid()) . "." . $file->extension())->toString())
            ]);
    }
}
