<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Attributes\Rule;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static string $view = 'filament.pages.change-password';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Change Password';
    protected static ?string $title = 'Change Your Password';

    public static function getSlug(): string
    {
        return 'change-password';
    }

    #[Rule('required|string')]
    public string $current_password = '';

    #[Rule('required|string')]
    public string $new_password = '';

    #[Rule('required|string')]
    public string $new_password_confirmation = '';

    public function changePassword()
    {
        $this->validate([
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('The current password is incorrect.');
                    }
                }
            ],
            'new_password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'different:current_password'
            ],
            'new_password_confirmation' => 'required|string|same:new_password'
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!($user instanceof User)) {
            Notification::make()
                ->title('Error')
                ->body('User not found.')
                ->danger()
                ->send();
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password)
        ]);

        // Refresh session
        Auth::setUser($user);
        session()->regenerate();
        session()->put('password_hash_'.Auth::getDefaultDriver(), $user->getAuthPassword());

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        Notification::make()
            ->title('Password Updated')
            ->body('Your password has been changed successfully.')
            ->success()
            ->persistent()
            ->send();
    }
}