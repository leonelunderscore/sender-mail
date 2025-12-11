<?php

namespace App\Imports;

use App\Models\Campaign;
use App\Models\Recipient;
use Maatwebsite\Excel\Concerns\ToModel;

readonly class RecipientImport implements ToModel
{

    private Campaign $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function model(array $row): ?Recipient
    {
        if ($row[0] && $email = $this->extractEmail($row[0])) {
            $recipient = new Recipient([
                'email' => $email,
                'campaign_id' => $this->campaign->id
            ]);

            if (Recipient::where('campaign_id', $this->campaign->id)->where('email', $recipient->email)->doesntExist()) {
                return $recipient;
            }

        }
        return null;
    }

    private function extractEmail(string $email): ?string
    {
        $pattern = '/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i';
        if (preg_match($pattern, $email, $matches)) {
            return filter_var($matches[0], FILTER_VALIDATE_EMAIL) ? str($matches[0])->lower()->toString() : null;
        }
        return null;
    }
}
