<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use LogicException;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();
        if (! $user) {
            // This should not happen due to the EmailVerificationRequest, but it satisfies static analysis.
            throw new LogicException('User not authenticated.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($user instanceof MustVerifyEmail) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
