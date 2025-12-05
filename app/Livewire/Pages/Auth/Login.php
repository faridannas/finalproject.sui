<?php

namespace App\Livewire\Pages\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            if (Auth::user()->isAdmin()) {
                $this->redirect(route('admin.dashboard'), navigate: true);
            } else {
                $this->redirect(route('dashboard'), navigate: true);
            }
        }

        $this->addError('email', trans('auth.failed'));
    }

    public function render()
    {
        return view('livewire.pages.auth.login')->layout('layouts.login-layout');
    }
}