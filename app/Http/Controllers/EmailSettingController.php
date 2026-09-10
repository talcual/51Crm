<?php

namespace App\Http\Controllers;

use App\Models\EmailSetting;
use Illuminate\Http\Request;

class EmailSettingController extends Controller
{
    /**
     * Show the SMTP configuration form.
     */
    public function edit()
    {
        $emailSetting = EmailSetting::current();

        return view('settings.email.edit', compact('emailSetting'));
    }

    /**
     * Update the SMTP configuration.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|in:smtp,sendmail,log',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl,',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        $emailSetting = EmailSetting::current();

        // Keep the existing password if the field is left blank.
        if (empty($validated['mail_password'])) {
            unset($validated['mail_password']);
        }

        $emailSetting->update($validated);

        return redirect()->route('settings.email.edit')->with('success', 'Email settings updated successfully.');
    }
}
