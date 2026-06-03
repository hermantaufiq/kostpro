<?php

namespace App\Http\Middleware;

use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Database\Eloquent\Model;

class FilamentAuthenticate extends Middleware
{
    /**
     * Override Filament's default Authenticate middleware so that:
     * - If user is NOT logged in → redirect to admin login
     * - If user IS logged in but is NOT an admin → log them out and redirect to admin login
     * - If user IS logged in as admin → allow through
     *
     * This prevents the 403 Forbidden error when a regular (non-admin) user
     * tries to access the admin panel.
     */
    protected function authenticate($request, array $guards): void
    {
        $guard = Filament::auth();

        // User is not logged in at all → redirect to login
        if (! $guard->check()) {
            $this->unauthenticated($request, $guards);
            return;
        }

        $this->auth->shouldUse(Filament::getAuthGuard());

        /** @var Model $user */
        $user = $guard->user();

        $panel = Filament::getCurrentOrDefaultPanel();

        // Check if the user can access the admin panel
        $canAccess = $user instanceof FilamentUser
            ? $user->canAccessPanel($panel)
            : (config('app.env') === 'local');

        if (! $canAccess) {
            // Log them out from all guards and redirect to admin login
            // instead of throwing 403 Forbidden
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $this->unauthenticated($request, $guards);
            return;
        }
    }

    protected function redirectTo($request): ?string
    {
        return Filament::getLoginUrl();
    }
}
