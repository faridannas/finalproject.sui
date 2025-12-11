<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

// Define layout explicitly
use function Livewire\Volt\layout;
layout('layouts.guest');

use function Livewire\Volt\state;
use function Livewire\Volt\rules;

state(['email' => '']);

rules(['email' => 'required|email']);

$sendPasswordResetLink = function () {
    $this->validate();

    $status = Password::sendResetLink(
        $this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        $this->addError('email', __($status));
        return;
    }

    $this->reset('email');

    session()->flash('status', __($status));
};

?>

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="auth-card w-full max-w-md">
        <a href="/" class="flex justify-center mb-6">
            <img src="{{ asset('images/logoseblak.jpeg') }}" alt="Seblak Umi AI Logo" class="h-16 w-16 object-contain rounded-lg">
        </a>
        
        <h1 class="auth-title">Lupa Password?</h1>
        <p class="auth-subtitle">
            Jangan khawatir! Masukkan email Anda dan kami akan mengirimkan link reset password.
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-6">
            <!-- Email Address -->
            <div>
                <label for="email" class="form-label">Email Address</label>
                <div class="relative">
                    <input 
                        wire:model="email" 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        required 
                        autofocus 
                        placeholder="nama@email.com"
                    />
                </div>
                @error('email') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="auth-button">
                Kirim Link Reset Password
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-600">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="auth-link" wire:navigate>
                Kembali ke Login
            </a>
        </div>
    </div>
</div>
