<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    protected $fillable = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    protected $casts = [
        'mail_password' => 'encrypted',
    ];

    protected $hidden = [
        'mail_password',
    ];

    /**
     * Get the singleton settings record, creating a default one if none exists.
     */
    public static function current(): self
    {
        return static::first() ?? static::create([]);
    }

    /**
     * Apply these SMTP settings to the runtime mail configuration.
     */
    public function applyToConfig(): void
    {
        config([
            'mail.default' => $this->mail_mailer ?: config('mail.default'),
            'mail.mailers.smtp.host' => $this->mail_host,
            'mail.mailers.smtp.port' => $this->mail_port,
            'mail.mailers.smtp.username' => $this->mail_username,
            'mail.mailers.smtp.password' => $this->mail_password,
            'mail.mailers.smtp.encryption' => $this->mail_encryption ?: null,
            'mail.from.address' => $this->mail_from_address ?: config('mail.from.address'),
            'mail.from.name' => $this->mail_from_name ?: config('mail.from.name'),
        ]);
    }
}
