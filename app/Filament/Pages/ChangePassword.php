<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan ini ada!
use Livewire\Attributes\Validate;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static string $view = 'filament.pages.change-password';
    protected static bool $shouldRegisterNavigation = false;

    public static function getSlug(): string
    {
        return 'change-password'; // Ini yang menentukan URL!
    }
    #[Validate('required|string|min:8')]
    public string $current_password = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $new_password = '';

    public string $new_password_confirmation = '';

    public function changePassword()
    {
        $this->validate();
    
        /** @var User $user */
        $user = Auth::user();
    
        if (!($user instanceof User)) {
            $this->addError('current_password', 'User not found.');
            return;
        }
    
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }
    
        // **Update password**
        $user->password = Hash::make($this->new_password);
        $user->save();
    
        // **Login ulang tanpa menghapus sesi**
        Auth::login($user);
        Auth::setUser($user); // Tambahkan ini!
    
        // **Regenerasi session agar tidak logout**
        session()->put('password_hash_' . Auth::getDefaultDriver(), $user->getAuthPassword());
    
        // Reset form setelah sukses
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    
        // **Tampilkan notifikasi Filament**
        Notification::make()
            ->title('Success')
            ->body('Password successfully changed.')
            ->success()
            ->send();
    }
}
