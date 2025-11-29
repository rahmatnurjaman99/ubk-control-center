<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as GoogleUser;
use Throwable;

class GoogleOAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $request->session()->put('url.intended', $request->input('intended', url()->previous()));

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            /** @var GoogleUser $googleUser */
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('filament.admin.auth.login')
                ->withErrors([
                    'email' => __('Unable to sign in with Google at the moment. Please try again.'),
                ]);
        }

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ],
        );

        // if (! $user->password) {
        //     $user->forceFill([
        //         'password' => Hash::make(Str::password()),
        //     ])->save();
        // }

        Auth::login($user, remember: true);

        $request->session()->regenerate();

        return redirect()->intended(
            $request->session()->pull('url.intended', Filament::getDefaultPanel()->getUrl()),
        );
    }
}
