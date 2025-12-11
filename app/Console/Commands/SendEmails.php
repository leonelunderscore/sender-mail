<?php

namespace App\Console\Commands;

use App\Mail\CustomMail;
use App\Models\Campaign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get emails to send and send them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $campaigns = Campaign::with('smtp')->where('is_active', true)->whereHas('recipients', fn($query) => $query->where('sent', false))->get();

        $this->info('Sending emails');
        foreach ($campaigns as $campaign) {
            $this->info('Handling campaign ' . $campaign->name);
            $recipient = $campaign->recipients()->where('sent', false)->first();
            if (!$recipient || !$campaign->smtp) {
                continue;
            }
            $mailerName = 'smtp_campaign_' . Str::slug((string)$campaign->id) . '_' . ($campaign->smtp->id ?? 'default');
            config([
                'mail.mailers.' . $mailerName => [
                    'transport' => 'smtp',
                    'host' => $campaign->smtp->host,
                    'port' => (int)$campaign->smtp->port,
                    'encryption' => $campaign->smtp->encryption ?: null,
                    'username' => $campaign->smtp->username,
                    'password' => $campaign->smtp->password,
                    'timeout' => null,
                    'auth_mode' => null,
                ],
            ]);
            Mail::mailer($mailerName)->to($recipient->email)->send(new CustomMail($campaign));
            $recipient->update(['sent' => true]);
            $this->info('Email sent to ' . $recipient->email);
            $this->info('Campaign ' . $campaign->name . ' handled');
        }
        $this->info('Emails sent');
    }
}
