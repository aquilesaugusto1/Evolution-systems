<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use LogicException;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        $skills = Skill::orderBy('categoria')->orderBy('nome')->get()->groupBy('categoria');
        $userSkills = $user->skills->pluck('pivot.nivel', 'id');

        return view('profile.edit', [
            'user' => $user,
            'skills' => $skills,
            'userSkills' => $userSkills,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateSkills(Request $request): RedirectResponse
    {
        $request->validate([
            'skills' => ['sometimes', 'array'],
            'skills.*' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $user = $request->user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        $user->skills()->sync($request->input('skills', []));

        return Redirect::route('profile.edit')->with('status', 'skills-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
