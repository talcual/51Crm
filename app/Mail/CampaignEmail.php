<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EmailCampaign $campaign)
    {
    }

    public function build(): self
    {
        return $this->subject($this->campaign->subject)
            ->html($this->campaign->content);
    }
}
