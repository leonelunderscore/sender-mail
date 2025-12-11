<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use App\Models\Campaign;
use App\Models\Recipient;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CampaignInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User')
                    ->placeholder('-'),
                TextEntry::make('smtp.reference')
                    ->label('Smtp')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('subject'),
                TextEntry::make('html')->html()
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('id')
                    ->label('Status')
                    ->formatStateUsing(function (Campaign $record) {
                        return Recipient::where('campaign_id', $record->id)->where('sent', true)->count() . "/" . Recipient::where('campaign_id', $record->id)->count();
                    })
                    ->placeholder('-'),
            ]);
    }
}
