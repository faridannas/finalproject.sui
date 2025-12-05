<div class="min-h-screen flex items-center justify-center px-4">
    <div class="auth-card w-full max-w-md">
        <a href="/" class="flex justify-center mb-6">
            <img src="{{ asset('images/logoseblak.jpeg') }}" alt="Seblak Umi AI Logo" class="h-16 w-16 object-contain rounded-lg">
        </a>
        <h1 class="auth-title">Welcome Back!</h1>
        <p class="auth-subtitle">Please sign in to continue to your Seblak UMI account.</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="login" class="space-y-6">
            <!-- Email Address -->
            <div>
                <label for="email" class="form-label">Email Address</label>
                <input wire:model.blur="email" id="email" class="form-input" type="email" required autofocus autocomplete="username" placeholder="you@example.com" />
                @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="form-label">Password</label>
                <input wire:model.blur="password" id="password" class="form-input" type="password" required autocomplete="current-password" placeholder="••••••••" />
                @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model.live="remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link text-sm" wire:navigate>
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="auth-button">
                    Sign In
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="auth-link" wire:navigate>
                Sign up here
            </a>
        </p>
    </div>
</div>
