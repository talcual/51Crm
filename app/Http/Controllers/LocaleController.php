<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch the application's display language and remember it in the session.
     */
    public function switch(Request $request, string $locale)
    {
        if (array_key_exists($locale, config('app.available_locales', []))) {
            $request->session()->put('locale', $locale);
        }

        return redirect()->back();
    }
}
