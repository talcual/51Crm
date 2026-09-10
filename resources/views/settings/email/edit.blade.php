<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Email / SMTP Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-6">
                        Configure the SMTP server used to send email campaigns and other notifications.
                    </p>

                    <form method="POST" action="{{ route('settings.email.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="mail_mailer" value="Mail Driver" />
                                <select id="mail_mailer" name="mail_mailer" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    @foreach(['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'log' => 'Log (testing)'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('mail_mailer', $emailSetting->mail_mailer) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('mail_mailer')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="mail_encryption" value="Encryption" />
                                <select id="mail_encryption" name="mail_encryption" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" @selected(old('mail_encryption', $emailSetting->mail_encryption) === null)>None</option>
                                    <option value="tls" @selected(old('mail_encryption', $emailSetting->mail_encryption) === 'tls')>TLS</option>
                                    <option value="ssl" @selected(old('mail_encryption', $emailSetting->mail_encryption) === 'ssl')>SSL</option>
                                </select>
                                <x-input-error :messages="$errors->get('mail_encryption')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="mail_host" value="SMTP Host" />
                                <x-text-input id="mail_host" name="mail_host" type="text" class="mt-1 block w-full" :value="old('mail_host', $emailSetting->mail_host)" placeholder="smtp.mailgun.org" />
                                <x-input-error :messages="$errors->get('mail_host')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="mail_port" value="SMTP Port" />
                                <x-text-input id="mail_port" name="mail_port" type="text" class="mt-1 block w-full" :value="old('mail_port', $emailSetting->mail_port)" placeholder="587" />
                                <x-input-error :messages="$errors->get('mail_port')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="mail_username" value="SMTP Username" />
                                <x-text-input id="mail_username" name="mail_username" type="text" class="mt-1 block w-full" :value="old('mail_username', $emailSetting->mail_username)" autocomplete="off" />
                                <x-input-error :messages="$errors->get('mail_username')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="mail_password" value="SMTP Password" />
                                <x-text-input id="mail_password" name="mail_password" type="password" class="mt-1 block w-full" autocomplete="new-password" placeholder="{{ $emailSetting->mail_password ? 'Leave blank to keep current password' : '' }}" />
                                <x-input-error :messages="$errors->get('mail_password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="mail_from_address" value="From Address" />
                                <x-text-input id="mail_from_address" name="mail_from_address" type="email" class="mt-1 block w-full" :value="old('mail_from_address', $emailSetting->mail_from_address)" required />
                                <x-input-error :messages="$errors->get('mail_from_address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="mail_from_name" value="From Name" />
                                <x-text-input id="mail_from_name" name="mail_from_name" type="text" class="mt-1 block w-full" :value="old('mail_from_name', $emailSetting->mail_from_name)" required />
                                <x-input-error :messages="$errors->get('mail_from_name')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-primary-button>Save Settings</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
