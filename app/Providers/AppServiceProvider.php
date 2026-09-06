<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['fortify.home' => '/' . admin_path()]);

        Fortify::loginView(fn () => view('auth.login'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn ($request) => view('auth.reset-password', ['request' => $request]));

        // Dual-identity authentication (Email OR Username)
        Fortify::authenticateUsing(function (Request $request) {
            $login = $request->input('email') ?? $request->input('username') ?? $request->input('login');

            if (! $login) {
                return null;
            }

            $user = User::where('email', $login)
                ->orWhere('username', $login)
                ->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });

        // Password reset action handler
        Fortify::resetUserPasswordsUsing(function (User $user, array $input) {
            Validator::make($input, [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ])->validate();

            $user->forceFill([
                'password' => Hash::make($input['password']),
            ])->save();
        });

        RateLimiter::for('login', function (Request $request) {
            $key = Str::transliterate(Str::lower($request->input('email') ?? $request->input('username') ?? $request->input('login') ?? '').'|'.$request->ip());
            return Limit::perMinute(5)->by($key);
        });
    }
}
