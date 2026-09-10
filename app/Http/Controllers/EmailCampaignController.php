<?php

namespace App\Http\Controllers;

use App\Mail\CampaignEmail;
use App\Models\Client;
use App\Models\EmailCampaign;
use App\Models\EmailSetting;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $campaigns = EmailCampaign::withCount('recipients')
            ->latest()
            ->paginate(15);

        return view('email-campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('email-campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCampaign($request);

        $campaign = EmailCampaign::create($validated + [
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('email-campaigns.show', $campaign)->with('success', 'Campaign created successfully.');
    }

    public function show(EmailCampaign $emailCampaign)
    {
        $emailCampaign->load('recipients', 'createdBy');

        return view('email-campaigns.show', ['campaign' => $emailCampaign]);
    }

    public function edit(EmailCampaign $emailCampaign)
    {
        return view('email-campaigns.edit', ['campaign' => $emailCampaign]);
    }

    public function update(Request $request, EmailCampaign $emailCampaign)
    {
        $validated = $this->validateCampaign($request);

        $emailCampaign->update($validated);

        return redirect()->route('email-campaigns.show', $emailCampaign)->with('success', 'Campaign updated successfully.');
    }

    public function destroy(EmailCampaign $emailCampaign)
    {
        $emailCampaign->delete();

        return redirect()->route('email-campaigns.index')->with('success', 'Campaign deleted successfully.');
    }

    /**
     * Build the recipient list and send the campaign via the configured SMTP settings.
     */
    public function send(EmailCampaign $emailCampaign)
    {
        if ($emailCampaign->status === 'sent' || $emailCampaign->status === 'sending') {
            return redirect()->route('email-campaigns.show', $emailCampaign)->with('error', 'This campaign has already been sent or is currently sending.');
        }

        EmailSetting::current()->applyToConfig();

        $emailCampaign->recipients()->delete();
        $this->buildRecipients($emailCampaign);

        $emailCampaign->update(['status' => 'sending']);

        $failures = 0;

        foreach ($emailCampaign->recipients as $recipient) {
            try {
                Mail::to($recipient->email)->send(new CampaignEmail($emailCampaign));

                $recipient->update(['status' => 'sent', 'sent_at' => now()]);
            } catch (\Throwable $e) {
                $failures++;
                $recipient->update(['status' => 'failed', 'error' => $e->getMessage()]);
            }
        }

        $emailCampaign->update([
            'status' => $failures > 0 && $failures === $emailCampaign->recipients()->count() ? 'failed' : 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->route('email-campaigns.show', $emailCampaign)->with('success', "Campaign sent. {$failures} failure(s).");
    }

    private function validateCampaign(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all_clients,all_leads,custom',
            'custom_recipients' => 'nullable|required_if:recipient_type,custom|string',
            'scheduled_at' => 'nullable|date',
        ]);
    }

    /**
     * Create recipient rows based on the campaign's recipient type.
     */
    private function buildRecipients(EmailCampaign $emailCampaign): void
    {
        if ($emailCampaign->recipient_type === 'all_clients') {
            Client::whereNotNull('email')->where('email', '!=', '')->get()->each(
                fn (Client $client) => $emailCampaign->recipients()->create([
                    'email' => $client->email,
                    'name' => $client->name,
                    'client_id' => $client->id,
                ])
            );

            return;
        }

        if ($emailCampaign->recipient_type === 'all_leads') {
            Lead::whereNotNull('email')->where('email', '!=', '')->get()->each(
                fn (Lead $lead) => $emailCampaign->recipients()->create([
                    'email' => $lead->email,
                    'name' => $lead->name,
                    'lead_id' => $lead->id,
                ])
            );

            return;
        }

        // custom: one email per line or comma-separated
        $emails = preg_split('/[,\n\r]+/', (string) $emailCampaign->custom_recipients);

        foreach ($emails as $email) {
            $email = trim($email);

            if ($email !== '') {
                $emailCampaign->recipients()->create(['email' => $email]);
            }
        }
    }
}
